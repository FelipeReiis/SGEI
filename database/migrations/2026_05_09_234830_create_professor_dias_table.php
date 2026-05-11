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
        Schema::create('professor_dias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_professor');
            $table->foreign('id_professor')->references('id')->on('professors')->onDelete('cascade');
            $table->unsignedBigInteger('id_turma');
            $table->foreign('id_turma')->references('id')->on('turmas')->onDelete('cascade');
            $table->string('dia', length:10);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professor_dias');
    }
};
