<?php

namespace App\Services\Watch\Parser\Sites;

use App\Exceptions\ApiArgumentException;
use App\Models\WatchAuthor;
use App\Notifications\Telegram;
use App\Repositories\Eloquent\WatchAuthorRepository;
use App\Repositories\Eloquent\WatchBookRepository;
use App\Repositories\Eloquent\WatchSeriesRepository;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use App\Repositories\Interfaces\WatchBookRepositoryInterface;
use App\Repositories\Interfaces\WatchSeriesRepositoryInterface;
use App\Services\Http\HttpClient;
use App\Services\Http\HttpClientInterface;
use App\Services\Images\ImageService;
use App\Services\Watch\Parser\ParserInterface;
use App\ValueObject\WatchBook;
use App\ValueObject\WatchSeries;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\DomCrawler\Crawler;

class Litres extends AbstractParser implements ParserInterface
{
    /**
     * @var WatchAuthorRepositoryInterface
     */
    protected WatchAuthorRepositoryInterface $authorRepository;

    /**
     * @var WatchSeriesRepositoryInterface
     */
    protected WatchSeriesRepositoryInterface $seriesRepository;

    /**
     * @var WatchBookRepositoryInterface
     */
    protected WatchBookRepositoryInterface $bookRepository;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;

    /**
     * @var WatchSeries[]
     */
    public array $result = [];

    public function __construct()
    {
        $this->authorRepository = new WatchAuthorRepository();
        $this->seriesRepository = new WatchSeriesRepository();
        $this->bookRepository = new WatchBookRepository();
        $this->httpClient = new HttpClient();
    }

    /**
     * @param string $url
     * @throws ApiArgumentException
     * @throws GuzzleException
     */
    public function parseAuthorInfo(string $url): void
    {
        // Guzzle
        $response = $this->httpClient->get($url);

        // XML errors
        libxml_use_internal_errors(true);

        // Crawler
        (new Crawler($response))->filter('.author_name > h1')
            ->reduce(function (Crawler $node) use ($url) {
                $fullName = explode(' ', $node->text());
                if (!$this->authorRepository->isExist(['column' => 'url', 'value' => $url])) {
                    $this->authorRepository->store([
                        'firstname' => $fullName[0],
                        'lastname' => $fullName[1],
                        'source' => 'litres',
                        'url' => $url,
                        'active' => true,
                    ]);
                }
            });
    }

    /**
     * @param WatchAuthor $author
     * @throws ApiArgumentException
     * @throws GuzzleException
     */
    public function parseBookList(WatchAuthor $author): void
    {
        // Guzzle
        $response = $this->httpClient->get($author->url);

        // XML errors
        libxml_use_internal_errors(true);

        // Crawler
        (new Crawler($response))->filter('.person-page-list__content_sequence')
            ->reduce(function (Crawler $node) {

                $watchSeries = new WatchSeries();
                if ($node->children('.letter_icon')->count()) {
                    $node->children('.letter_icon')
                        ->reduce(function (Crawler $seriesLine, $i) use ($node) {
                            $watchSeries = new WatchSeries();
                            if ($seriesLine->children('h3')->count()) {
                                $watchSeries->title = $seriesLine->children('h3 > a')->text();
                                $watchSeries->link = env('LITRES_HOST') . substr_replace($seriesLine->children('h3 > a')->attr('href'), '', 0, 1);
                            } else {
                                $watchSeries->title = $seriesLine->text();
                                $watchSeries->link = '';
                            }

                            $node->filter('.arts_by_letter')
                            ->eq($i)
                            ->reduce(function (Crawler $booksLine) use ($watchSeries) {
                                $booksLine->children('.arts_by_alphabet_item')
                                    ->reduce(function (Crawler $booksItem) use ($watchSeries) {
                                        $watchBook = new WatchBook();
                                        $watchBook->link = env('LITRES_HOST') . substr_replace(
                                            $booksItem->children('.art_name_container > a')->attr('href'),'', 0, 1
                                        );
                                        $watchBook->title = preg_replace(
                                            '/\d+\.\ ?/',
                                            '',
                                            trim($booksItem->children('.art_name_container > a')->text(),chr(0xC2).chr(0xA0))
                                        );
                                        $watchSeries->books[] = $watchBook;
                                    }
                                );
                            });
                            $this->result[] = $watchSeries;
                        }
                    );
                }
            }
        );

        // Series
        foreach ($this->result as $item) {
            if ($item->link) {
                if (!$this->seriesRepository->isExist(['column' => 'url', 'value' => $item->link])) {
                    $this->seriesRepository->store([
                        'author_id' => $author->id,
                        'title' => $item->title,
                        'url' => $item->link,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                }
            }

            // Books for current series
            foreach ($item->books as $book) {
                if ($book->link) {
                    if (!$this->bookRepository->isExist(['column' => 'url', 'value' => $book->link])) {
                        $book->image = $this->getBookCover($book->link, env('LITRES_HOST'));
                        $this->bookRepository->store([
                            'author_id' => $author->id,
                            'image' => $book->image,
                            'title' => $book->title,
                            'url' => $book->link,
                            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                        ]);
                        Notification::send(
                            $this->makeMessage($author, $item, $book),
                            new Telegram()
                        );
                    }
                }
            }
        }
    }

    /**
     * @param string $path
     * @param string $host
     * @return string
     * @throws GuzzleException
     */
    public function getBookCover(string $path, string $host): string
    {
        // Guzzle
        $httpClient = new HttpClient();
        $response = $httpClient->get($path);

        // XML errors
        libxml_use_internal_errors(true);

        // Crawler
        $crawler = new Crawler($response);
        $imageLink = $crawler->filterXpath('//img')
            ->extract(array('src'));
        if ($imageLink[1]) {
            return (new ImageService('litres'))->getBookCover($imageLink[1]);
        }

        return 'https://library-b40.s3.eu-north-1.amazonaws.com/watch/noimage.png';
    }
}
