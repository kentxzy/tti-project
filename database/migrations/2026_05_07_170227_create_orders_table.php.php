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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); //this is customer name
            $table->foreignId('branch_id')->constrained()->onDelete('cascade'); //this is what kind branch was it
            $table->decimal('price', 10, 2); //total price
            $table->enum('status', [
                'pending',
                'verified',
                'dispatched',
                'delivered'
            ])->default('pending'); // this is the order status
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
