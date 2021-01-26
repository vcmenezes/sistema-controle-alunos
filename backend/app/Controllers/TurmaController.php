<?php

namespace App\Controllers;

use App\Models\Aluno;
use App\Models\AlunoTurma;
use App\Models\Turma;
use Core\Model;
use Core\Response;
use JsonException;
use Throwable;

class TurmaController
{
    /**
     * @return Response
     * @throws JsonException
     */
    public function index(): Response
    {
        $turmas = Turma::all();
        return response()->json($turmas)->setStatus(200);
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
            $escolas = Turma::search($field, $value);
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
        $turma = $this->checkIfExist(Turma::class, $id);
        if ($turma instanceof Response) {
            return $turma;
        }
        return response()->json($turma->toArray())->setStatus(200);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function showAluno(int $id): Response
    {
        $turma = $this->checkIfExist(Turma::class, $id);
        if ($turma instanceof Response) {
            return $turma;
        }

        $alunoTurmas = AlunoTurma::all("id_turma = $id");
        $alunos = [];

        for ($i = 0, $iMax = count($alunoTurmas); $i < $iMax; ++$i) {
            $aluno = Aluno::find($alunoTurmas[$i]['id_aluno']);
            $alunos[$i] = $aluno->toArray();
        }

        return response()->json($alunos)->setStatus(200);
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
            $turma = new Turma();
            $turma->fromArray(request()->all());
            $turma->save();
            return response()->json($turma->toArray())->setStatus(201);
        } catch (Throwable $exception) {
            throw $exception;
        }
    }

    /**
     * @return Response
     * @throws JsonException
     * @throws Throwable
     */
    public function storeAluno(): Response
    {
        try {
            $idTurma = request()->get('id_turma');
            $idAluno = request()->get('id_aluno');
            $alunoTurmaExist = AlunoTurma::findFirst("id_turma = $idTurma AND id_aluno = $idAluno");
            if ($alunoTurmaExist) {
                return response()->setError('Já cadastrado');
            }
            $alunoTurma = new AlunoTurma();
            $alunoTurma->fromArray(request()->all());
            $alunoTurma->save();
            return response()->json($alunoTurma->toArray())->setStatus(201);
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
        $turma = $this->checkIfExist(Turma::class, $id);
        if ($turma instanceof Response) {
            return $turma;
        }

        $turma->fromArray(request()->all());
        $turma->save();

        return response()->json($turma->toArray())->setStatus(200);
    }

    public function delete(int $id): Response
    {
        // TODO - Verificar se existem alunos matriculados na turma antes da exclusão
        $turma = $this->checkIfExist(Turma::class, $id);
        if ($turma instanceof Response) {
            return $turma;
        }
        $turma->delete();

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
