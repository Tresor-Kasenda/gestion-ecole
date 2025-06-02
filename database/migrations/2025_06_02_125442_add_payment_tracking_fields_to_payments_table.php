<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->date('due_date')->nullable()->after('payment_date');
            $table->text('notes')->nullable()->after('due_date');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'due_date')) {
                $table->dropColumn('due_date');
            }
            if (Schema::hasColumn('payments', 'notes')) {
                $table->dropColumn('notes');
            }
        });
    }
};
