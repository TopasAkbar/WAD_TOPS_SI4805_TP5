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
        // ========== 1 =========
        // Create books table with necessary fields
        // Fields: id, title, author, published_year, is_available, created_at, updated_at
        Schema::create('books', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->string('title'); // Title of the book
            $table->string('author'); // Author of the book
            $table->year('published_year'); // Year the book was published
            $table->boolean('is_available')->default(true); // Availability of the book (default: true)
            $table->timestamps(); // Automatically adds created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
