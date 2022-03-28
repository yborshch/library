<?php

namespace App\Services\Http;

interface HttpClientInterface
{
    /**
     * @param string $url
     * @return string
     */
    public function get(string $url): string;
}
