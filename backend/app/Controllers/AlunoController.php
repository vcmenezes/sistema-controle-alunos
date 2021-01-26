<?php

namespace App\Controllers;

use App\Models\Aluno;
use App\Models\AlunoTurma;
use App\Models\Turma;
use Core\Model;
use Core\Response;
use JsonException;
use Throwable;

class AlunoController
{
    /**
     * @throws JsonException
     */
    public function index(): Response
    {
        $alunos = Aluno::all();
        return response()->json($alunos)->setStatus(200);
    }

    /**
     * @return Response
     * @throws JsonException
     */
    public function search(): Response
    {
        $field = request()->query('field');
        $value = request()->query('value');
        if ($field){
            $escolas = Aluno::search($field, $value);
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
        $aluno = $this->checkIfExist(Aluno::class, $id);
        if ($aluno instanceof Response) {
            return $aluno;
        }
        return response()->json($aluno->toArray())->setStatus(200);
    }

    /**
     * @param int $id
     * @return Response
     * @throws JsonException
     */
    public function showTurma(int $id): Response
    {
        $aluno = $this->checkIfExist(Aluno::class, $id);
        if ($aluno instanceof Response) {
            return $aluno;
        }

        $alunoTurmas = AlunoTurma::all("id_aluno = $id");
        $turmas = [];

        for ($i = 0, $iMax = count($alunoTurmas); $i < $iMax; ++$i) {
            $turma = Turma::find($alunoTurmas[$i]['id_turma']);
            $turmas[$i] = $turma->toArray();
        }

        return response()->json($turmas)->setStatus(200);
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
            $aluno = new Aluno();
            $aluno->fromArray(request()->all());
            $aluno->save();
            return response()->json($aluno->toArray())->setStatus(201);
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
        $aluno = $this->checkIfExist(Aluno::class, $id);
        if ($aluno instanceof Response) {
            return $aluno;
        }

        $aluno->fromArray(request()->all());
        $aluno->save();

        return response()->json($aluno->toArray())->setStatus(200);
    }

    public function delete(int $id): Response
    {
        $aluno = $this->checkIfExist(Aluno::class, $id);
        if ($aluno instanceof Response) {
            return $aluno;
        }
        $aluno->delete();

        return response()->setStatus(204);
    }

    public function deleteTurma(int $idAluno, int $idTurma)
    {
        $aluno = $this->checkIfExist(Aluno::class, $idAluno);
        if ($aluno instanceof Response) {
            return $aluno;
        }
        $turma = $this->checkIfExist(Turma::class, $idTurma);
        if ($turma instanceof Response) {
            return $turma;
        }

        $alunoTurma = AlunoTurma::findFirst("id_aluno = $idAluno AND id_turma = $idTurma")[0] ?? null;
        if (empty($alunoTurma)){
            return response()->setError('Registro não encontrado')->setStatus(422);
        }

        $alunoTurmaModel = new AlunoTurma();
        $alunoTurmaModel->fromArray($alunoTurma);
        $alunoTurmaModel->delete();

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
