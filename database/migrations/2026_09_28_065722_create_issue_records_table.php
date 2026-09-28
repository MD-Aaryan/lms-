<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('issue_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('issued_at');
            $table->date('due_at');
            $table->date('returned_at')->nullable();
            $table->decimal('fine', 8, 2)->default(0);
            $table->timestamps();

            // Lookups for "which copies of this book are still out".
            $table->index(['book_id', 'returned_at']);
            // Lookups for "what is this member still holding".
            $table->index(['user_id', 'returned_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('issue_records');
    }
};
