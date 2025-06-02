<?php

namespace Database\Seeders;

use App\Models\TypePayment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypePaymentSeeder extends Seeder
{
    public function run(): void
    {
        collect([
            ['name' => 'Minerval', 'price' => 100000],
            ['name' => "Frais d'inscription", 'price' => 25000],
            ['name' => "Frais de l'Etat", 'price' => 15000],
            ['name' => "Autre frais", 'price' => 10000]
        ])->each(fn ($type) => TypePayment::query()->create($type));
    }
}
