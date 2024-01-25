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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->integer('code_book')->unique();
            $table->string('titel', 20);
            $table->text('synopsis')->nullable();
            $table->string('isbn', 30);
            $table->string('writer', 50);
            $table->integer('page_amount');
            $table->integer('stock_amount');
            $table->integer('published');
            $table->string('category')->nullable();
            $table->string('image');
            $table->string('status');
            $table->timestamps();
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
