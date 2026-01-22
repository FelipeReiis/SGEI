<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class NiveisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveis = [
            ['id_curso' => 2, 'nivel' => 'Iniciante'],
            ['id_curso' => 2, 'nivel' => 'Intermediario'],
            ['id_curso' => 2, 'nivel' => 'Avançado'],

            // --- Níveis de Ballet (id_curso = 1) ---
            ['id_curso' => 1, 'nivel' => 'Baby class'],
            ['id_curso' => 1, 'nivel' => 'Iniciação'],
            ['id_curso' => 1, 'nivel' => 'Pre-primary'],
            ['id_curso' => 1, 'nivel' => 'Primary'],
            ['id_curso' => 1, 'nivel' => 'Pré-grades'],
            ['id_curso' => 1, 'nivel' => 'I grau'],
            ['id_curso' => 1, 'nivel' => 'II grau'],
            ['id_curso' => 1, 'nivel' => 'III grau'],
            ['id_curso' => 1, 'nivel' => 'IV grau'],
            ['id_curso' => 1, 'nivel' => 'V grau'],
            ['id_curso' => 1, 'nivel' => 'Interfoundation'],
            ['id_curso' => 1, 'nivel' => 'Intermediate'],
            ['id_curso' => 1, 'nivel' => 'Advanced Foundation'],
            ['id_curso' => 1, 'nivel' => 'Advanced I'],
            ['id_curso' => 1, 'nivel' => 'Advanced II'],
            ['id_curso' => 1, 'nivel' => 'Aula livre'],
            ['id_curso' => 1, 'nivel' => 'Corpo de Baile'],
            ['id_curso' => 1, 'nivel' => 'Master class'],
        ];

        DB::table('nivels')->insert($niveis);
    }
}
