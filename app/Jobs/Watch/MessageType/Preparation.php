<?php

namespace App\Jobs\Watch\MessageType;

use Illuminate\Database\Eloquent\Collection;

class Preparation implements MessageTypeInterface
{
    /**
     * @var string
     */
    public string $type = 'preparation';

    /**
     * @var Collection
     */
    public Collection $data;
}
