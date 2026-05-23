<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfissoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profissoes = [
            ['descricao' => 'Limpeza'],
            ['descricao' => 'Manutenção'],
            ['descricao' => 'Segurança'],
            ['descricao' => 'Administrativo'],
            ['descricao' => 'Professor'],
        ];

        DB::table('profissaos')->insert($profissoes);
    }
}
