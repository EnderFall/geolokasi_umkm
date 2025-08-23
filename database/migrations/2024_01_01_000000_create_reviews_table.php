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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->morphs('reviewable'); // reviewable_type, reviewable_id
            $table->integer('rating')->comment('Rating 1-5');
            $table->text('comment')->nullable();
            $table->json('images')->nullable()->comment('Array of image paths');
            $table->boolean('is_verified')->default(false);
            $table->timestamps();

            // Indexes
            
            $table->index(['user_id', 'reviewable_type', 'reviewable_id']);
            $table->index('rating');
            $table->index('is_verified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
