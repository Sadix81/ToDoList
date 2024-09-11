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
            $table->foreignId('user_id')->constrained('users')->cascadeOnUpdate();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('started_at')->nullable();
            $table->string('finished_at')->nullable();
            $table->string('priority')->nullable(); // 1 ta 4
            $table->string('reminder')->nullable();
            $table->string('label')->nullable();
            // $table->string('storage_id') // we have some storage part(home , routines , ext);
            $table->string('status')->default(0); // 1 => done;
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
