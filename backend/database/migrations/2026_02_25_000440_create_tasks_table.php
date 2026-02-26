<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            // Esta linha amarra a tarefa ao usuário dono dela!
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            $table->string('title'); // Título da tarefa
            $table->text('description')->nullable(); // Descrição (opcional)
            $table->boolean('is_completed')->default(false); // Status de conclusão
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};