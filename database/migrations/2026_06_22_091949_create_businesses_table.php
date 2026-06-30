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
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('business_name');
            $table->string('contact_name')->nullable();
            $table->string('mobile', 20);
            $table->string('phone', 20)->nullable();
            $table->string('city')->nullable();
            $table->string('category')->nullable();
            $table->string('source')->nullable();

            $table->enum('status', [
                'new',
                'called',
                'interested',
                'need_follow_up',
                'demo_sent',
                'customer',
                'rejected',
                'no_answer',
            ])->default('new');

            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('assigned_user_id');
            $table->index('mobile');
            $table->index('status');
            $table->index('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
