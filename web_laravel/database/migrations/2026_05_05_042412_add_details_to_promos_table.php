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
        Schema::table('promos', function (Blueprint $table) {
            $table->decimal('min_purchase', 10, 2)->default(0)->after('value');
            $table->integer('quota')->nullable()->after('min_purchase');
            $table->integer('used')->default(0)->after('quota');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['min_purchase', 'quota', 'used']);
        });
    }
};
