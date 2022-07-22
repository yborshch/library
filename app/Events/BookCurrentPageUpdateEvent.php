<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookCurrentPageUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public array $bookPages;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $bookPages)
    {
        $this->bookPages = $bookPages;
    }
}
