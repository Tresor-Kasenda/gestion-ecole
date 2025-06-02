<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('type_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('type_payments', 'price')) {
                $table->decimal('price', 10, 2)->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::table('type_payments', function (Blueprint $table) {
            if (Schema::hasColumn('type_payments', 'price')) {
                $table->dropColumn('price');
            }
        });
    }
};
