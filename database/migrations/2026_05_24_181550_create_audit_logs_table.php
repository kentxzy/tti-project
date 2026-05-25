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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Action type (created, updated, deleted, viewed, etc.)
            $table->string('action');
            
            // Model information
            $table->string('model_type')->nullable(); // Full namespace like App\Models\Order
            $table->unsignedBigInteger('model_id')->nullable();
            
            // Change tracking
            $table->json('old_values')->nullable(); // Previous values
            $table->json('new_values')->nullable(); // New values
            
            // Request information
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            // Description/Notes
            $table->text('description')->nullable();
            
            // Timestamps
            $table->timestamps();
            
            // Indexes for faster queries
            $table->index('user_id');
            $table->index('model_type');
            $table->index('action');
            $table->index('created_at');
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
