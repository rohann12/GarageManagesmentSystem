<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Database\Seeders\DatabaseSeeder;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->unsignedInteger('order_id')->autoIncrement();
            $table->string('customer_name', 50);
            $table->string('email', 50)->nullable();
            $table->string('vehicle_no', 10);
            $table->dateTime('start_time', 6);
            $table->dateTime('estimated_completed', 6);
            $table->integer('estimated_cost');
            $table->string('repairs', 100);
            $table->enum('status', ['available', 'ongoing', 'completed', ''])->default('available');
            $table->integer('priority')->default(0);
            $table->integer('customer_id')->nullable();
            $table->time('time_taken')->default('00:00:00');
            $table->unsignedInteger('assigned_to')->default(1)->nullable();
            $table->timestamps();            

        });
      
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
