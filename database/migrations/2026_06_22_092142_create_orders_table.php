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

            $table->foreignId('business_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('product_name');
            $table->unsignedBigInteger('amount')->default(0);

            $table->enum('status', [
                'new',
                'reviewing',
                'waiting_payment',
                'paid',
                'completed',
                'cancelled',
            ])->default('new');

            $table->text('description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('business_id');
            $table->index('user_id');
            $table->index('status');
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
