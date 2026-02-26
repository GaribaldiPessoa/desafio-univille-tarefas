<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;

// Rotas públicas (Cadastro e Login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Rotas protegidas (Só acessa quem tem o Token)
Route::middleware('auth:sanctum')->group(function () {
    
    // Rota opcional para ver dados do usuário logado
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Rota para transferir a tarefa para outro usuário
    Route::put('/tasks/{task}/transfer', [TaskController::class, 'transfer']);

    // NOVA: Rota para listar os usuários no Modal (É ELA QUE ESTAVA FALTANDO!)
    Route::get('/users', [TaskController::class, 'listUsers']);

    // Esta linha cria automaticamente as rotas de Listar, Criar, Ver, Editar e Excluir tarefas
    Route::apiResource('tasks', TaskController::class);
});