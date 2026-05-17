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
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_aluno');
            $table->foreign('id_aluno')->references('id')->on('alunos')->onDelete('cascade');
            $table->decimal('valor', total:8, places:2);
            $table->string('forma_pagamento', length:10)->nullable();
            $table->string('qtd_parcela', length:10)->nullable();
            $table->string('comprovante', length:255);
            $table->date('pago_em');
            $table->unsignedBigInteger('id_evento')->nullable();
            $table->foreign('id_evento')->references('id')->on('eventos')->onDelete('cascade');
            $table->unsignedBigInteger('id_mensalidade')->nullable();
            $table->foreign('id_mensalidade')->references('id')->on('mensalidades')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
