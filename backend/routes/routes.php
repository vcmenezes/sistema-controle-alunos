<?php

use App\Controllers\AlunoController;
use App\Controllers\EscolaController;
use App\Controllers\TurmaController;
use Core\Route;

// Retorna todas as escolas
Route::get('escolas', [EscolaController::class, 'index']);
// Pesquisar escolas
Route::get('escolas/search', [EscolaController::class, 'search']);
// Retorna determinada escola
Route::get('escolas/{id_escola}', [EscolaController::class, 'show']);
// Cadastra uma nova escola
Route::post('escolas', [EscolaController::class, 'store']);
// Atualiza determinada escola
Route::put('escolas/{id_escola}', [EscolaController::class, 'update']);
// Exclui determinada escola
Route::delete('escolas/{id_escola}', [EscolaController::class, 'delete']);
// Cadastra uma turma em determinada escola
Route::post('escolas/{id_escola}/turmas', [EscolaController::class, 'storeTurma']);
// Retorna as turmas de uma determinada escola
Route::get('escolas/{id_escola}/turmas', [EscolaController::class, 'showTurma']);
// Retorna total de alunos de uma escola
Route::get('escolas/{id_escola}/alunos/total', [EscolaController::class, 'totalAluno']);

// Retorna todas as turmas
Route::get('turmas', [TurmaController::class, 'index']);
// Pesquisar turmas
Route::get('turmas/search', [TurmaController::class, 'search']);
// Retorna determinada turma
Route::get('turmas/{id_turma}', [TurmaController::class, 'show']);
// Cadastra nova turma
Route::post('turmas', [TurmaController::class, 'store']);
// Atualiza determinada turma
Route::put('turmas/{id_turma}', [TurmaController::class, 'update']);
// Exclui determinada turma
Route::delete('turmas/{id_turma}', [TurmaController::class, 'delete']);
// Retorna os alunos de uma determinada turma
Route::get('turmas/{id_turma}/alunos', [TurmaController::class, 'showAluno']);
// Cadastra um aluno em uma turma
Route::post('turmas/alunos', [TurmaController::class, 'storeAluno']);

// Retorna todos os alunos
Route::get('alunos', [AlunoController::class, 'index']);
// Pesquisar alunos
Route::get('alunos/search', [AlunoController::class, 'search']);
// Retorna determinado aluno
Route::get('alunos/{id_aluno}', [AlunoController::class, 'show']);
// Cadastra novo aluno
Route::post('alunos', [AlunoController::class, 'store']);
// Atualiza determinado aluno
Route::put('alunos/{id_aluno}', [AlunoController::class, 'update']);
// Exclui determinado aluno
Route::delete('alunos/{id_aluno}', [AlunoController::class, 'delete']);
// Retorna as turmas em que um determinado aluno está cadastrado
Route::get('alunos/{id_aluno}/turmas', [AlunoController::class, 'showTurma']);
// Remove um aluno de uma turma
Route::delete('alunos/{id_aluno}/turmas/{id_turma}', [AlunoController::class, 'deleteTurma']);

