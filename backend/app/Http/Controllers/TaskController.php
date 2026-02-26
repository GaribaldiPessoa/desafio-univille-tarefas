<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Começa pegando apenas as tarefas do usuário logado
        $query = $request->user()->tasks();

        // 1. FILTRO POR STATUS (Concluídas, Pendentes ou Todas)
        if ($request->has('status') && $request->status !== 'all') {
            $isCompleted = $request->status === 'completed';
            $query->where('is_completed', $isCompleted);
        }

        // 2. ORDENAÇÃO (Crescente ou Decrescente por data de criação)
        $direction = $request->input('sort', 'desc'); // desc = mais novas primeiro
        $query->orderBy('created_at', $direction);

        // Retorna 5 tarefas por página para podermos ver a paginação funcionando rápido
return $query->paginate(5);
    }

    // Criar uma nova tarefa para o usuário logado
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return response()->json($task, 201);
    }

    // Detalhar uma tarefa específica
    public function show(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }
        return $task;
    }

    // Atualizar título, descrição ou marcar como concluída
    public function update(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $task->update($request->all());
        return $task;
    }

    // Excluir a tarefa
    public function destroy(Request $request, Task $task)
    {
        if ($task->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Não autorizado'], 403);
        }

        $task->delete();
        return response()->json(null, 204);
    }
public function transfer(Request $request, Task $task)
 {
     // Valida se o e-mail foi enviado
     $request->validate(['email' => 'required|email']);

     // Garante que a tarefa pertence ao usuário logado
     if ($task->user_id !== $request->user()->id) {
         return response()->json(['message' => 'Não autorizado'], 403);
     }

     // Busca o usuário destino no banco
     $targetUser = \App\Models\User::where('email', $request->email)->first();

     if (!$targetUser) {
         return response()->json(['message' => 'Usuário destino não encontrado'], 404);
     }

     // Transfere a propriedade da tarefa
     $task->user_id = $targetUser->id;
     $task->save();

     return response()->json(['message' => 'Tarefa transferida com sucesso!']);
 }
 public function listUsers(Request $request)
    {
        // Começa a query excluindo o usuário logado
        $query = \App\Models\User::where('id', '!=', $request->user()->id);

        // Se o frontend enviar uma palavra de busca, filtra por nome ou e-mail
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('email', 'like', $searchTerm)
                  ->orWhere('name', 'like', $searchTerm);
            });
        }

        // Retorna o resultado paginado
        return $query->paginate(5);
    }
 }