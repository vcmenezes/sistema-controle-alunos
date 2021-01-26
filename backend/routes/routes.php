<?php

use App\Controllers\EscolaController;
use Core\Route;

Route::get('escolas', [EscolaController::class, 'index']);
Route::get('escolas/{id_escola}', [EscolaController::class, 'show']);
Route::post('escolas', [EscolaController::class, 'store']);
Route::put('escolas/{id_escola}', [EscolaController::class, 'update']);
Route::delete('escolas/{id_escola}', [EscolaController::class, 'delete']);

Route::get('escola/{id_escola}/turma', 'TurmaController@index');
Route::get('escola/{id_escola}/turma/{id_turma}', 'TurmaController@show');
Route::post('escola/{id_escola}/turma', 'TurmaController@store');
Route::put('escola/{id_escola}/turma/{id_turma}', 'TurmaController@update');
Route::delete('escola/{id_escola}/turma/{id_turma}', 'TurmaController@delete');

Route::get('escola/{id_escola}/turma/{id_turma}/aluno', 'AlunoController@index');
Route::get('escola/{id_escola}/turma/{id_turma}/aluno/{id_aluno}', 'AlunoController@show');
Route::post('escola/{id_escola}/turma/{id_turma}/aluno', 'AlunoController@store');
Route::put('escola/{id_escola}/turma/{id_turma}/aluno/{id_aluno}', 'AlunoController@update');
Route::delete('escola/{id_escola}/turma/{id_turma}/aluno/{id_aluno}', 'AlunoController@delete');

Route::get('turma', 'TurmaController@index');
Route::get('turma/{id_turma}', 'TurmaController@show');
Route::post('turma', 'TurmaController@store');
Route::put('turma/{id_turma}', 'TurmaController@update');
Route::delete('turma/{id_turma}', 'TurmaController@delete');

Route::get('aluno', 'AlunoController@index');
Route::get('aluno/{id_aluno}', 'AlunoController@show');
Route::post('aluno', 'AlunoController@store');
Route::put('aluno/{id_aluno}', 'AlunoController@update');
Route::delete('aluno/{id_aluno}', 'AlunoController@delete');
