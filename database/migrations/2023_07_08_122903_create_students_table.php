<?php

use App\Models\Classe;
use App\Models\Option;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Option::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Classe::class)
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->string('lastname');
            $table->string('firstname');
            $table->string('address');
            $table->date('birthdays');
            $table->string('matricule', 8)->unique();
            $table->enum('gender', ['male', 'female']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
