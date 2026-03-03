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
        Schema::create('alunos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_resp_fin');
            $table->foreign('id_resp_fin')->references('id')->on('pessoas')->onDelete('cascade');
            $table->unsignedBigInteger('id_resp_pedag');
            $table->foreign('id_resp_pedag')->references('id')->on('pessoas')->onDelete('cascade');
            $table->unsignedBigInteger('id_turma')->nullable();
            $table->foreign('id_turma')->references('id')->on('turmas')->onDelete('cascade');
            $table->unsignedBigInteger('id_pessoa');
            $table->foreign('id_pessoa')->references('id')->on('pessoas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alunos');
    }
};
