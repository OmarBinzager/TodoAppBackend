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
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('picture')->nullable();
            $table->foreignId('category')->constrained('categories')->onDelete('cascade');
            $table->foreignId('priority')->constrained('priorities')->onDelete('cascade');
            $table->foreignId('status')->constrained('statuses')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();
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
