<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {

            $table->string('business_name')
                ->nullable();

            $table->string('phone', 30)
                ->nullable();

            $table->string('city', 100)
                ->nullable();

            $table->string('category', 100)
                ->nullable();

            $table->string('source', 100)
                ->nullable();

            $table->string('status', 30)
                ->default('new')
                ->index();

            $table->foreignId('assigned_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('description')
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {

            $table->dropConstrainedForeignId(
                'assigned_user_id'
            );

            $table->dropColumn([
                'business_name',
                'phone',
                'city',
                'category',
                'source',
                'status',
                'description',
            ]);
        });
    }
};