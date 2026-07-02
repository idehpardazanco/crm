<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_logs', function (Blueprint $table): void {
            $table->id();

            $table->nullableMorphs('sendable');

            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('provider')->default('payam_matni');
            $table->string('from_number', 30)->nullable();
            $table->string('mobile', 20);
            $table->text('message');

            $table->string('status', 30)->default('queued');
            $table->json('request_payload')->nullable();
            $table->json('provider_response')->nullable();
            $table->text('error_message')->nullable();

            $table->string('response_code')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['mobile', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
    }
};