<?php

namespace App\Jobs\Watch;

use App\Exceptions\ApiArgumentException;
use App\Jobs\Watch\MessageType\MessageTypeInterface;
use App\Jobs\Watch\MessageType\Watch;
use App\Models\WatchAuthor;
use App\Repositories\Eloquent\WatchAuthorRepository;
use App\Repositories\Interfaces\WatchAuthorRepositoryInterface;
use App\Services\Watch\Parser\Sites\Litres;
use App\Services\Watch\Parser\Sites\Loveread;
use App\Services\Watch\WatchService;
use App\Traits\CheckEnvironmentTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WatchAuthorsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CheckEnvironmentTrait;

    const AMOUNT_AUTHORS_IN_MESSAGE = 2;

    /**
     * @var WatchAuthorRepositoryInterface
     */
    protected WatchAuthorRepositoryInterface $repository;

    /**
     * @var MessageTypeInterface
     */
    protected MessageTypeInterface $message;

    /**
     * @var int
     */
    public int $timeout = 90;

    public function __construct(MessageTypeInterface $message)
    {
        $this->repository = new WatchAuthorRepository();
        $this->message = $message;
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        match ($this->message->type) {
            'preparation' => $this->preparation(),
            'watch' => $this->watch(),
            default => throw new Exception('Invalid type of message in watch queue')
        };
    }

    protected function preparation()
    {
        $countAuthors = WatchAuthor::count();
        $countMessages = ceil($countAuthors/self::AMOUNT_AUTHORS_IN_MESSAGE);

        for ($page = 0; $page < $countMessages; $page++) {
            $message = new Watch();
            $message->type = 'watch';
            $message->data = $this->repository->getWatchAuthors($page, self::AMOUNT_AUTHORS_IN_MESSAGE);
            $job = new self($message);
            dispatch($job->onQueue('watch'));
        }
    }

    /**
     * @throws ApiArgumentException
     */
    protected function watch()
    {
        foreach ($this->message->data as $author) {
            $urlInfo = parse_url($author['url']);
            $parser = match ($urlInfo['host']) {
                'loveread.ec' => new Loveread(),
                'litres.ua' => new Litres(),
                default =>  throw new ApiArgumentException(
                    $this->filterErrorMessage(__METHOD__ . ', ' . trans('api.watch.resource')),
                )
            };

            $watchService = new WatchService();
            $watchService->run($author, $parser);
        }
    }
}
