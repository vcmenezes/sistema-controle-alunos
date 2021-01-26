<?php

namespace App\Controllers;

use Core\Response;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Throwable;

class EstudaApiController
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://estuda.com/apps/api/',
            'timeout' => 2.0,
            'verify' => false,
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
    }

    /**
     * @param $cep
     * @return Response
     * @throws GuzzleException|Throwable
     */
    public function cep($cep): Response
    {
        try {
            $response = $this->client->request('GET', 'cep', [
                'query' => ['q' => $cep]
            ]);
            $body = $response->getBody()->getContents();

            return response()->setContent($body)->setStatus(200);
        }catch (Throwable $exception){
            throw $exception;
        }
    }

    /**
     * @return Response
     * @throws GuzzleException|Throwable
     */
    public function escola(): Response
    {
        try {
            $response = $this->client->request('GET', 'variaveis_escolas', [
                'query' => [
                    'q' => request()->query('q'),
                    'uf' => request()->query('uf')
                ]
            ]);
            $body = $response->getBody()->getContents();

            return response()->setContent($body)->setStatus(200);
        }catch (Throwable $exception){
            throw $exception;
        }
    }
}
