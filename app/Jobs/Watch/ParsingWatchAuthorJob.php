<?php

namespace App\Jobs\Watch;

use App\Exceptions\ApiArgumentException;
use App\Services\Watch\WatchService;
use App\Services\Watch\WatchServiceInterface;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Request;

class ParsingWatchAuthorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public int $timeout = 90;

    /**
     * @var WatchServiceInterface
     */
    protected WatchServiceInterface $watchService;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->url = $request->get('url');
        $this->watchService = new WatchService();
    }

    /**
     * @throws GuzzleException
     * @throws ApiArgumentException
     */
    public function handle()
    {
        $this->watchService->parseAuthorPage($this->url);
    }
}
