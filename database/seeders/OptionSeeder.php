<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            "Commerciale",
            "Pedagogique",
            "Scientifique",
            "Mecanique Generale",
            "Coupe et Couture",
            "Electricite",
            "Mecanique Auto",
            "Literaire",
            "Education de base"
        ])->each(fn ($name) => Option::query()->create(['nom' => $name]));
    }
}
