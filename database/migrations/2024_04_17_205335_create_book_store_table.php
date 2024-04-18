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
        Schema::create('book_store', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('store_id');
            $table->timestamps();

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_store');
    }
};
