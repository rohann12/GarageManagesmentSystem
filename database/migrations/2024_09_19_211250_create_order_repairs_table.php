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
        Schema::create('order_repairs', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('repair_id');
            $table->integer('user_id');
            $table->integer('user_Xp');
            $table->integer('Xp');
            $table->time('estimated_time');  // Store duration as string (e.g., '00:20:00')
            $table->time('time_taken');      // Store duration as string (e.g., '00:15:00')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_repairs');
    }
};
