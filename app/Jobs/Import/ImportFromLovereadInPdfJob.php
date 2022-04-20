<?php

namespace App\Jobs\Import;

use App\Exceptions\ApiArgumentException;
use App\Repositories\Eloquent\BookRepository;
use App\Repositories\Eloquent\MessageRepository;
use App\Services\Import\BookServiceInterface;
use App\Services\Import\FileType\PDF;
use App\Services\Import\Parser\Sites\Loveread;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Symfony\Component\HttpFoundation\Request;

class ImportFromLovereadInPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var int
     */
    public int $timeout = 90;

    /**
     * @var BookServiceInterface
     */
    protected BookServiceInterface $bookService;

    /**
     * @var string
     */
    protected string $url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(BookServiceInterface $bookService, Request $request)
    {
        $this->url = $request->get('url');
        $this->bookService = $bookService;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws ApiArgumentException
     */
    public function handle()
    {
        $parser = new Loveread();
        $parser->setUrlToBookInformation($this->url);

        $book = $this->bookService->createBook($parser);
        $book->type = 'pdf';

        $saveToFile = $this->bookService->saveTo(
            new PDF(),
            $book
        );
        if ($saveToFile) {
            unset($book->context);
            $savedBook = (new BookRepository())->store((array) $book);
            (new MessageRepository())->store([
                'book_id' => $savedBook->id,
                'book' => $savedBook,
                'action' => 'Импорт'
            ]);
        }
    }
}
