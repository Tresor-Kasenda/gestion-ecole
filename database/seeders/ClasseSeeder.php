<?php

namespace Database\Seeders;

use App\Models\Classe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClasseSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            'Premiere',
            'Deuxieme',
            'Troisieme',
            'Quatrieme',
            'Cinquieme',
            'Sixieme',
            'Septieme',
            'Huitieme',
        ])->each(fn ($class) => Classe::query()->create(['name' => $class]));
    }
}
