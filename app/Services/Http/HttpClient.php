<?php

namespace App\Services\Http;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class HttpClient implements HttpClientInterface
{
    /**
     * @param string $url
     * @return string
     * @throws GuzzleException
     */
    public function get(string $url): string
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=utf-8',
            'Referer' => 'https://google.com'
        ];
        try {
            $response = $client->get($url, ['headers' => $headers]);

            return (string) $response->getBody();
        } catch (\Exception $e) {
            Log::critical('Import', ['HttpClient' => $e->getMessage()]);

            return $e->getMessage();
        }
    }
}
