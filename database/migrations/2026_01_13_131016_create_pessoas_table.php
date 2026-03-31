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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', length:70);
            $table->string('email', length:50);
            $table->unsignedInteger('id_profissao')->nullable();
            $table->foreign('id_profissao')->references('id')->on('profissaos')->onDelete('cascade');
            $table->string('telefone', length:15);
            $table->string('rg', length:12);
            $table->string('cpf', length:12);
            $table->date('data_nascimento');
            $table->unsignedBigInteger('id_end');
            $table->foreign('id_end')->references('id')->on('enderecos')->onDelete('cascade');
            $table->boolean('funcionario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
