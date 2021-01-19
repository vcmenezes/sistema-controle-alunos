<?php

namespace App\Controllers;

use App\Models\Escola;
use Core\Response;
use JsonException;
use Throwable;

class EscolaController
{
    private Response $response;

    public function __construct()
    {
        $this->response = response();
    }

    /**
     * @throws JsonException
     */
    public function index(): Response
    {
        $escolas = Escola::all();
        return $this->response->json($escolas)->setStatus(200);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function show(int $id): Response
    {
        $escola = Escola::find($id);
        if (empty($escola)) {
            return $this->response->setError("Não há registro para o ID: $id")->setStatus(422);
        }
        return $this->response->json($escola)->setStatus(200);
    }

    /**
     * @return Response
     * @throws JsonException
     * @throws Throwable
     */
    public function store(): Response
    {
        try {
            $escola = new Escola();
            $escola->nome = request()->get('nome');
            $escola->endereco = request()->get('endereco');
            $escola->data = request()->get('data');
            $escola->situacao = request()->get('situacao');
            $newEscola = $escola->save();
            return $this->response->json($newEscola)->setStatus(201);
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function update(int $id): Response
    {
        $escola = Escola::find($id);
        if (empty($escola)) {
            return $this->response->setError("Não há registro para o ID: $id")->setStatus(422);
        }

        $escola = new Escola();
        $escola->id = $id;
        $escola->nome = request()->get('nome');
        $escola->endereco = request()->get('endereco');
        $escola->data = request()->get('data');
        $escola->situacao = request()->get('situacao');
        $updatedEscola = $escola->save();
        return $this->response->json($updatedEscola)->setStatus(200);
    }

    public function delete(int $id)
    {
        $escola = Escola::find($id);
        if (empty($escola)) {
            return $this->response->setError("Não há registro para o ID: $id")->setStatus(422);
        }
        $escola = new Escola();
        $escola->id = $id;
        return $escola->delete();
    }
}
