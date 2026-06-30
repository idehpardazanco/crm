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
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('business_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('sms_template_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('mobile', 20);
            $table->text('final_message');

            $table->enum('status', [
                'queued',
                'sent',
                'failed',
            ])->default('queued');

            $table->json('provider_response')->nullable();
            $table->timestamp('sent_at')->nullable();

            $table->timestamps();

            $table->index('business_id');
            $table->index('user_id');
            $table->index('sms_template_id');
            $table->index('mobile');
            $table->index('status');
            $table->index('sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};
