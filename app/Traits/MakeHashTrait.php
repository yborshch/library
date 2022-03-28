<?php

namespace App\Traits;

use Carbon\Carbon;

trait MakeHashTrait
{
    /**
     * @return string
     */
    protected function makeErrorHash(): string
    {
        return hash('md5', Carbon::now()->toString());
    }
}
