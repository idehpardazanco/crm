<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {

            $table->id();

            $table->string('business_name')
                ->nullable();

            $table->string('name');

            $table->string('mobile')
                ->index();

            $table->string('phone')
                ->nullable();

            $table->string('email')
                ->nullable();


            $table->string('city')
                ->nullable();


            $table->string('category')
                ->nullable();


            $table->string('source')
                ->nullable();


            $table->enum('status', [
                'new',
                'active',
                'inactive',
                'customer'
            ])
            ->default('new');


            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();


            $table->text('address')
                ->nullable();


            $table->text('description')
                ->nullable();


            $table->timestamps();

            $table->softDeletes();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};