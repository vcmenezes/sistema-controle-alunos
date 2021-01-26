<?php

namespace App\Controllers;

use App\Models\AlunoTurma;
use App\Models\Escola;
use App\Models\Turma;
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
     * @return Response
     * @throws JsonException
     */
    public function search(): Response
    {
        $field = request()->query('field');
        $value = request()->query('value');
        if ($field) {
            $escolas = Escola::search($field, $value);
            return response()->json($escolas)->setStatus(200);
        }
        return response()->setError('Query inválida')->setStatus(422);
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
     * @param int|null $idEscola
     * @return Response
     * @throws JsonException
     */
    public function showTurma(?int $idEscola): Response
    {
        $turmas = Turma::all("id_escola = $idEscola");
        return response()->json($turmas)->setStatus(200);
    }

    /**
     * @param int|null $id
     * @return Response
     * @throws JsonException
     */
    public function totalAluno(?int $id): Response
    {
        // TODO - Arrumar a lógica aqui
        $escola = $this->checkIfExist(Escola::class, $id);
        if ($escola instanceof Response) {
            return $escola;
        }

        $countTotal = 0;
        $turmas = Turma::all("id_escola = $id");
        foreach ($turmas as $turma){
            $count = AlunoTurma::count('*', "id_turma = $turma[id]");
            $countTotal += $count;
        }

        return response()->json(['count' => $countTotal])->setStatus(200);
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
     * @param int|null $idEscola
     * @return Response
     * @throws JsonException
     * @throws Throwable
     */
    public function storeTurma(?int $idEscola): Response
    {
        try {
            $turma = new Turma();
            $turma->fromArray(request()->all());
            $turma->set('id_escola', $idEscola);
            $turma->save();
            return response()->json($turma->toArray())->setStatus(201);
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

    public function delete(int $id): Response
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
        // TODO - Remover e colocar em um local geral
        /** @var Model $model */
        $model = new $modelClass;
        $found = $model::find($id);
        if (empty($found) || $found === false) {
            return response()->setError("Não há registro para o ID: $id")->setStatus(422);
        }
        return $found;
    }
}
