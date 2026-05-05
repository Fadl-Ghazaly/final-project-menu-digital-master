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
            $table->text('description')->nullable()->after('name');
            $table->string('promo_type')->default('diskon')->after('description'); // diskon, bundling, free_item
            $table->string('image')->nullable()->after('promo_type');
            $table->boolean('is_banner')->default(false)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            $table->dropColumn(['description', 'promo_type', 'image', 'is_banner']);
        });
    }
};
