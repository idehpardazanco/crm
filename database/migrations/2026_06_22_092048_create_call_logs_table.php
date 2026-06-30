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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->enum('result', [
                'no_answer',
                'unavailable',
                'interested',
                'demo_requested',
                'price_requested',
                'call_later',
                'customer',
                'not_interested',
            ]);

            $table->text('description')->nullable();
            $table->timestamp('next_follow_up_at')->nullable();

            $table->enum('status_after_call', [
                'new',
                'called',
                'interested',
                'need_follow_up',
                'demo_sent',
                'customer',
                'rejected',
                'no_answer',
            ])->nullable();

            $table->timestamps();

            $table->index('business_id');
            $table->index('user_id');
            $table->index('result');
            $table->index('next_follow_up_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
