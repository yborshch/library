<?php

namespace App\Listeners;

use App\Events\BookCurrentPageUpdateEvent;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class BookCurrentPageUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param BookCurrentPageUpdateEvent $event
     * @return void
     */
    public function handle(BookCurrentPageUpdateEvent $event)
    {
        if ($event->bookPages['current'] === $event->bookPages['new']) {
            Book::where('id', $event->bookPages['id'])->update([
                "read" => true
            ]);
        }
    }
}
