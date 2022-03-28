<?php

namespace App\ValueObject;

class File
{
    /**
     * @var string
     */
    public string $filename = '';

    /**
     * @var int
     */
    public int $size = 0;

    /**
     * @var string
     */
    public string $extension;
}
