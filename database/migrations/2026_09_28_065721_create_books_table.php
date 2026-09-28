<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->unsignedSmallInteger('published_year')->nullable();
            // Copies currently on the shelf: issuing a book decrements this, returning one adds it back.
            $table->unsignedInteger('stock')->default(1);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['title', 'author']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
