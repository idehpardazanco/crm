<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'contacts',
            function (Blueprint $table) {
                $table->id();

                $table
                    ->string('business_name')
                    ->nullable();

                $table->string('name');

                $table
                    ->string('mobile', 20)
                    ->index();

                $table
                    ->string('phone', 30)
                    ->nullable();

                $table
                    ->string('email')
                    ->nullable();

                $table
                    ->string('city', 100)
                    ->nullable();

                $table
                    ->string('category', 100)
                    ->nullable();

                $table
                    ->string('source', 100)
                    ->nullable();

                $table
                    ->string('status', 30)
                    ->default('new')
                    ->index();

                $table
                    ->foreignId('assigned_user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table
                    ->text('address')
                    ->nullable();

                $table
                    ->text('description')
                    ->nullable();

                $table->timestamps();

                $table->softDeletes();
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists(
            'contacts'
        );
    }
};