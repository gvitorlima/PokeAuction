<?php

namespace App\Contracts;

use Exception;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

abstract class Client
{
    protected ClientInterface $client;

    public function sendRequest(string $url, string $method = "GET", array $options = null): ResponseInterface|array
    {
        try {

            return $this->client->request($method, $url, $options ?? []);
        } catch (Exception $requestError) {
            throw $requestError;
        }
    }

    // @TODO fazer esse trem caso a requisição dê erro
    protected function retry()
    {
    }
}
