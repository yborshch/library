<?php

namespace App\Jobs\Watch\MessageType;

use Illuminate\Database\Eloquent\Collection;

class Watch implements MessageTypeInterface
{
    /**
     * @var string
     */
    public string $type = 'watch';

    /**
     * @var Collection
     */
    public Collection $data;
}
