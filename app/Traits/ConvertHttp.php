<?php

namespace App\Traits;

trait ConvertHttp
{
    /**
     * @param string $link
     * @return string
     */
    public function httpsToHttp(string $link): string
    {
        $linkComponentArray = parse_url($link);

        return 'http://' . $linkComponentArray['host'] . $linkComponentArray['path'] . '?' . $linkComponentArray['query'];
    }
}
