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

class Loveread extends AbstractParser implements ParserInterface
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
     * @var array
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
        (new Crawler($response))->filter('.contents > h2')
            ->reduce(function (Crawler $node) use ($url) {
                $fullName = explode(' ', $node->text());
                $this->authorRepository->store([
                    'firstname' => $fullName[0],
                    'lastname' => $fullName[1],
                    'url' => $url,
                    'active' => true,
                ]);
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
        (new Crawler($response))->filter('.sr_book > li')
            ->reduce(function (Crawler $node) {
                $watchSeries = new WatchSeries();
                if ($node->children('span > a')->count() > 0) {
                    $watchSeries->link = env('LOVEREAD_HOST') . $node->children('span > a')->attr('href');
                    $watchSeries->id = str_replace('http://loveread.ec/series-books.php?id=', '', $watchSeries->link);
                    $watchSeries->title = str_replace('Серия: ', '', trim($node->children('span > a')->text()));
                } else {
                    $watchSeries->title = str_replace('Серия: ', '', trim($node->children('span')->text()));
                    $watchSeries->link = '';
                }

                $node->children('ul > li')
                    ->reduce(function (Crawler $bookNode) use ($watchSeries) {
                        $watchBook = new WatchBook();
                        $watchBook->link = env('LOVEREAD_HOST') . trim($bookNode->children('a')->attr('href'));
                        $watchBook->id = str_replace('http://loveread.ec/view_global.php?id=', '', $watchBook->link);
                        $watchBook->title = preg_replace(
                            '/\d+\. /',
                            '',
                            trim($bookNode->children('a')->text(),chr(0xC2).chr(0xA0))
                        );
                        $watchSeries->books[] = $watchBook;
                    });
                $this->result[] = $watchSeries;
            });

        // Series
        foreach ($this->result as $item) {
            if ($item->id) {
                if (!$this->seriesRepository->isExist(['column' => 'series_id', 'value' => $item->id])) {
                    $this->seriesRepository->store([
                        'author_id' => $author->id,
                        'series_id' => $item->id,
                        'title' => $item->title,
                        'url' => $item->link,
                        'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                        'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);
                }
            }

            // Books for current series
            foreach ($item->books as $book) {
                if ($book->id) {
                    if (!$this->bookRepository->isExist(['column' => 'book_id', 'value' => $book->id])) {
                        $book->image = $this->getBookCover($book->link, env('LOVEREAD_HOST'));
                        $this->bookRepository->store([
                            'author_id' => $author->id,
                            'book_id' => $book->id,
                            'series_id' => $item->id,
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
        $imageLink = $host . $crawler->filter('.margin-right_8')->attr('src');

        return (new ImageService('loveread'))->getBookCover($imageLink);
    }
}
