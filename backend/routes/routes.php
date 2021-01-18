<?php

use Core\Route;

Route::get('escola', 'EscolaController@index');
Route::get('escola/{id_escola}', 'EscolaController@show');
Route::post('escola', 'EscolaController@store');
Route::put('escola/{id_escola}', 'EscolaController@update');
Route::delete('escola/{id_escola}', 'EscolaController@delete');

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
