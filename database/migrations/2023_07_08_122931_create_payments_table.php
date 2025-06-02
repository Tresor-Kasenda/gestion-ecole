<?php

use App\Models\Option;
use App\Models\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Student::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(Option::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->date('payment_date');
            $table->decimal('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
