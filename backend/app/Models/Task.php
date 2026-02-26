<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <-- ESTA É A PONTE QUE FALTAVA
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // Permite que o Laravel salve esses campos no banco
    protected $fillable = [
        'title', 
        'description', 
        'is_completed', 
        'user_id'
    ];

    /**
     * Relação inversa: Uma tarefa pertence a um usuário.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}