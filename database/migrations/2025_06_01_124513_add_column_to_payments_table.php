<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->after('amount');
            $table->decimal('balance', 10, 2)->default(0)->after('total_amount');
            $table->boolean('is_completed')->default(false)->after('balance');
            $table->decimal('outstanding_paid', 10, 2)->nullable()->after('balance');
            $table->decimal('outstanding_original', 10, 2)->nullable()->after('outstanding_paid');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['total_amount', 'balance', 'is_completed', 'outstanding_paid', 'outstanding_original']);
        });
    }
};
