<?php

namespace App\Services\Watch;

use App\Exceptions\ApiArgumentException;
use App\Models\WatchAuthor;
use App\Services\Watch\Parser\ParserInterface;
use App\Services\Watch\Parser\Sites\Litres;
use App\Services\Watch\Parser\Sites\Loveread;
use App\Traits\CheckEnvironmentTrait;
use GuzzleHttp\Exception\GuzzleException;


class WatchService implements WatchServiceInterface
{
    use CheckEnvironmentTrait;

    /**
     * @throws GuzzleException
     * @throws ApiArgumentException
     */
    public function parseAuthorPage(string $url): void
    {
        $urlInfo = parse_url($url);

        match ($urlInfo['host']) {
            'loveread.ec' => (new Loveread())->parseAuthorInfo($url),
            'litres.ua' => (new Litres())->parseAuthorInfo($url),
            default =>  throw new ApiArgumentException(
                $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.watch.resource')),
                'data => ' . $url
            )
        };
    }

    public function run(WatchAuthor $author, ParserInterface $parser): void
    {
        $parser->parseBookList($author);
    }
}
