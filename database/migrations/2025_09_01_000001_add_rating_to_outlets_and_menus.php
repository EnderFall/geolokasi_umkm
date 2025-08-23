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
        // Add rating columns to outlets table
        Schema::table('outlets', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->default(0)->comment('Average rating 0.0-5.0');
            $table->integer('total_reviews')->default(0)->comment('Total number of reviews');
        });

        // Add rating columns to menus table
        Schema::table('menus', function (Blueprint $table) {
            $table->decimal('rating', 3, 1)->default(0)->comment('Average rating 0.0-5.0');
            $table->integer('total_reviews')->default(0)->comment('Total number of reviews');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove rating columns from outlets table
        Schema::table('outlets', function (Blueprint $table) {
            $table->dropColumn(['rating', 'total_reviews']);
        });

        // Remove rating columns from menus table
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn(['rating', 'total_reviews']);
        });
    }
};
