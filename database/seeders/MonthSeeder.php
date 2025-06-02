<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        collect([
            'Janvier',
            'Fevrier',
            'Mars',
            "Avril",
            'Mai',
            'Juin',
            'Juillet',
            'Aout',
            'Septembre',
            'Octobre',
            'Novembre',
            'Decembre',
            'Inscription',
            '1e Tranche',
            '2e Tranche',
            '3e Tranche',
        ])->each(fn($month) => Month::query()->create(['name' => $month]));
    }
}
