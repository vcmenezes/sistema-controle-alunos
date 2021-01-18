<?php

namespace App\Controllers;

use App\Models\Escola;
use JsonException;

class EscolaController
{
    /**
     * @return false|string
     */
    public function index()
    {
        // TODO - Testar sem o json_encode
        return Escola::all();
    }

    /**
     * @param int $id
     * @return false|string
     * @throws JsonException
     */
    public function show(int $id)
    {
        $escola = Escola::find($id);
        return json_encode($escola, JSON_THROW_ON_ERROR);
    }

    public function store()
    {
        $escola = new Escola();
        $escola->nome = request()->get('nome');
        $escola->endereco = request()->get('endereco');
        $escola->data = request()->get('data');
        $escola->situacao = request()->get('situacao');
        return $escola->save();
    }

    public function update(int $id)
    {
        $escola = Escola::find($id);
        $escola->nome = request()->get('nome');
        $escola->endereco = request()->get('endereco');
        $escola->data = request()->get('data');
        $escola->situacao = request()->get('situacao');
        return $escola->save();
    }

    public function delete(int $id)
    {
        $escola = Escola::find($id);
        return $escola->delete();
    }
}
