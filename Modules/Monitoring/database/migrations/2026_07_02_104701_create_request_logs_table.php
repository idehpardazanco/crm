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
        Schema::create('request_logs', function ($table) {
            $table->id();

            $table->string('method');
            $table->string('url');
            $table->string('ip')->nullable();

            $table->foreignId('user_id')->nullable();

            $table->json('headers')->nullable();
            $table->json('payload')->nullable();

            $table->integer('status_code')->nullable();
            $table->integer('duration')->nullable(); // ms

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_logs');
    }
};
