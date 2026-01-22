<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cursos = [
            ['nome' => 'Ballet'],
            ['nome' => 'Jazz'],
            ['nome' => 'Teatro'],
            ['nome' => 'Dança de Salão'],
        ];

        DB::table('cursos')->insert($cursos);

    }
}
