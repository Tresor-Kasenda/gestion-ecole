<?php

namespace Database\Seeders;

use App\Models\Ecole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EcoleSeeder extends Seeder
{
    public function run(): void
    {
        Ecole::query()
            ->create([
                "nom" => "LE PARISIEN",
                'adresse' => "N°53 Av de l'eglises",
                "email" => "cc421871@gmail.com",
                "telephone" => "0971908560",
            ]);
    }
}
