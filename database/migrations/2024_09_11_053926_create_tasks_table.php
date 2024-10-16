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
            $table->foreignId('owner_id')->constrained('users')->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnUpdate();
            $table->foreignId('group_id')->nullable()->constrained('groups')->cascadeOnUpdate();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('started_at')->nullable();
            $table->string('finished_at')->nullable();
            $table->string('priority'); // 1 ta 3
            $table->integer('status')->default(0); // 1 => done;
            $table->string('image')->nullable();
            $table->timestamps();
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
