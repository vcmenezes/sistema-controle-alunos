<?php

namespace App\Controllers;

use App\Models\Escola;
use Core\Model;
use Core\Response;
use JsonException;
use Throwable;

class EscolaController
{
    /**
     * @throws JsonException
     */
    public function index(): Response
    {
        $escolas = Escola::all();
        return response()->json($escolas)->setStatus(200);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function show(int $id): Response
    {
        $escola = $this->checkIfExist(Escola::class, $id);
        if ($escola instanceof Response) {
            return $escola;
        }
        return response()->json($escola->toArray())->setStatus(200);
    }

    /**
     * @return Response
     * @throws JsonException
     * @throws Throwable
     */
    public function store(): Response
    {
        // TODO - Validar input
        try {
            $escola = new Escola();
            $escola->fromArray(request()->all());
            $escola->save();
            return response()->json($escola->toArray())->setStatus(201);
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
        $escola = $this->checkIfExist(Escola::class, $id);
        if ($escola instanceof Response) {
            return $escola;
        }

        $escola->fromArray(request()->all());
        $escola->save();

        return response()->json($escola->toArray())->setStatus(200);
    }

    public function delete(int $id)
    {
        $escola = $this->checkIfExist(Escola::class, $id);
        if ($escola instanceof Response) {
            return $escola;
        }
        $escola->delete();

        return response()->setStatus(204);
    }

    /**
     * @param $modelClass
     * @param int $id
     * @return Model|Response
     */
    private function checkIfExist(string $modelClass, int $id)
    {
        /** @var Model $model */
        $model = new $modelClass;
        $found = $model::find($id);
        if (empty($found) || $found === false) {
            return response()->setError("Não há registro para o ID: $id")->setStatus(422);
        }
        return $found;
    }
}
