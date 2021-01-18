<?php

namespace App\Controllers;

use App\Models\Turma;
use JsonException;

class TurmaController
{
    /**
     * @param int $idEscola
     * @return false|string
     * @throws JsonException
     */
    public function index(int $idEscola)
    {
        $turmas = Turma::all("id_escola = $idEscola");
        return json_encode($turmas, JSON_THROW_ON_ERROR);
    }

    /**
     * @param int $idEscola
     * @param int $id
     * @return false|string
     * @throws JsonException
     */
    public function show(int $idEscola, int $id)
    {
        $turma = Turma::findFirst("id_escola = $idEscola AND id = $id");
        return json_encode($turma, JSON_THROW_ON_ERROR);
    }

    public function store(int $idEscola)
    {
        $turma = new Turma();
        $turma->nivel = request()->get('nivel');
        $turma->turno = request()->get('turno');
        $turma->ano = request()->get('ano');
        $turma->serie = request()->get('serie');
        $turma->id_escola = $idEscola;
        return $turma->save();
    }
}
