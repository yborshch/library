<?php

namespace App\Traits;

use Carbon\Carbon;

trait FileNameGenerate
{
    /**
     * @return string
     */
    public function getFileName(): string
    {
        return md5(Carbon::now()->format('U.u'));
    }
}
