<?php

namespace App\Services\Import\Parser\Sites;

use App\Services\Book\Builder\BookBuilder;
use App\Services\Http\HttpClient;
use App\Services\Http\HttpClientInterface;
use App\Services\Images\ImageService;
use App\Services\Import\Parser\ParserInterface;
use App\ValueObject\Book;
use DOMDocument;
use DOMXPath;
use GuzzleHttp\Exception\GuzzleException;

class Loveread implements ParserInterface
{
    /**
     * @var int
     */
    protected int $bookIdOnLoveared;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;

    /**
     * @var string
     */
    protected string $urlToBookInformation;

    public function __construct()
    {
        $this->httpClient = new HttpClient();
    }

    /**
     * @param string $url
     * @return void
     */
    public function setUrlToBookInformation(string $url): void
    {
        $this->urlToBookInformation = $url;
    }

    /**
     * @return Book
     * @throws GuzzleException
     */
    public function getBookInformation(): Book
    {
        // Set book id on Loveread
        $this->bookIdOnLoveared = str_replace(env('LOVEREAD_HOST') . 'view_global.php?id=', '', $this->urlToBookInformation);

        // Guzzle
        $response = $this->httpClient->get($this->urlToBookInformation);

        // XML errors
        libxml_use_internal_errors(true);

        // DOM
        $doc = new DOMDocument();
        $doc->loadHTML($response);

        // BookBuilder
        $builder = new BookBuilder();

        // DOM XPath
        $xpath = new DOMXPath($doc);
        $info = $xpath->evaluate('//td[@class="span_str"]//span');
        $links = $xpath->evaluate('//td[@class="span_str"]//a');

        // Parse book data
        foreach ($info as $item) {
            if (mb_strripos($item->textContent, 'Название', 0, "utf-8") !== false) {
                 $builder->setTitle($item->nextSibling->textContent);
            }
            if (mb_strripos($item->textContent, 'Год', 0, "utf-8") !== false) {
                $builder->setYear($item->nextSibling->textContent);
            }
            if (mb_strripos($item->textContent, 'Страниц', 0, "utf-8") !== false) {
                $builder->setPages($item->nextSibling->textContent);
            }
            if (mb_strripos($item->textContent, 'Серия', 0, "utf-8") !== false) {
                foreach ($links as $link) {
                    if (str_contains($link->getAttribute('href'), 'series-books.php?id=')) {
                        $builder->setSeries(trim($link->textContent));
                    }
                }
            }
            if (mb_strripos($item->textContent, 'Автор', 0, "utf-8") !== false) {
                foreach ($links as $link) {
                    if (str_contains($link->getAttribute('href'), 'biography-author.php?author=')) {
                        $builder->setAuthor(trim($link->textContent));
                    }
                }
            }
        }

        // Description
        $descriptionXpath = $xpath->evaluate('//p[@class="span_str"]')[0]->textContent;
        $builder->setDescription(
            trim(substr($descriptionXpath, 0, strpos($descriptionXpath, 'В нашей библиотеке')))
        );

        // Url to context
        $linkToContext = $xpath->evaluate('//table[@class="table_view_gl"]//tr[3]/td/p[2]/a');
        foreach ($linkToContext as $link) {
            if (str_contains($link->getAttribute('href'), 'read_book.php?id=')) {
                $builder->setUrlToContext($link->getAttribute('href'));
            }
        }

        // Link to image
        $imageDiv = $xpath->evaluate('//img[@class="margin-right_8"]');
        $linkToImage = $imageDiv->item(0)->getAttribute('src');
        $builder->setUrlToImage($linkToImage);

        // Set source
        $builder->setSource(Book::BOOK_IMPORT);

        // Set source link
        $builder->setSourceLink($this->urlToBookInformation);

        // Get book
        $book = $builder->getBook();

        // Image download
        (new ImageService(env('LOVEREAD_HOST')))->download($book);

        return $book;
    }

    /**
     * @param Book $bookInfo
     * @return array
     * @throws GuzzleException
     */
    public function getBookContext(Book $bookInfo): array
    {
        $result = [];
        if (!$bookInfo->pages) {
            $bookInfo->pages = $this->getMaxPageFromPagination(env('LOVEREAD_HOST') . $bookInfo->urlToContext);
        }

        for ($p = 1; $p <= $bookInfo->pages; $p++) {
            $result[] = $this->getCurrentPageContext(env('LOVEREAD_HOST') . 'read_book.php?id=' . $this->bookIdOnLoveared . '&p=' . $p);
        }

        return $result;
    }

    /**
     * @param string $url
     * @return int
     * @throws GuzzleException
     */
    protected function getMaxPageFromPagination(string $url): int
    {
        // Guzzle
        $response = $this->httpClient->get($url);

        // XML errors
        libxml_use_internal_errors(true);

        // DOM
        $doc = new DOMDocument();
        $doc->loadHTML($response);

        // DOM XPath
        $xpath = new DOMXPath($doc);
        $context = $xpath->evaluate('//div[@class="navigation"]//a');

        // Parse pagination and sort max number page
        $maxNumberPage = 0;
        foreach ($context as $item) {

            $currentPage = (int) str_replace(
                'read_book.php?id=' . $this->bookIdOnLoveared . '&p=',
                '',
                $item->getAttribute('href')
            );

            if ($currentPage > $maxNumberPage) {
                $maxNumberPage = $currentPage;
            }
        }

        return $maxNumberPage;
    }

    /**
     * @param string $url
     * @return array
     * @throws GuzzleException
     */
    protected function getCurrentPageContext(string $url): array
    {
        // Guzzle
        $response = $this->httpClient->get($url);

        // XML errors
        libxml_use_internal_errors(true);

        // DOM
        $doc = new DOMDocument();
        $doc->loadHTML($response);

        $result = [];

        // DOM XPath
        $xpath = new DOMXPath($doc);
        $block = $xpath->evaluate('//p[@class="MsoNormal"]');
        foreach ($block as $item) {
            $line = trim($item->textContent);
            if (strlen($line) > 0) {
                $result[] = $line;
            }
        }

        return $result;
    }
}
