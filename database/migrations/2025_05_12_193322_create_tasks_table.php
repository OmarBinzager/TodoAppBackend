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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('picture')->nullable();
            $table->foreignId('category')->nullable()->constrained('categories')->onDelete("no action");
            $table->foreignId('priority')->nullable()->constrained('priorities')->onDelete('no action');
            $table->foreignId('status')->constrained('statuses')->onDelete('no action');
            $table->date('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};