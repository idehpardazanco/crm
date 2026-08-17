<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('activity_logs')) {
            Schema::create('activity_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable();
                $table->string('action');
                $table->string('module')->nullable();
                $table->json('meta')->nullable();
                $table->timestamps();
            });

            return;
        }

        if (! Schema::hasColumn('activity_logs', 'user_id')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('activity_logs', 'action')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('action')
                    ->default('unknown')
                    ->after('user_id');
            });
        }

        if (! Schema::hasColumn('activity_logs', 'module')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->string('module')
                    ->nullable()
                    ->after('action');
            });
        }

        if (! Schema::hasColumn('activity_logs', 'meta')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->json('meta')
                    ->nullable()
                    ->after('module');
            });
        }

        if (! Schema::hasColumn('activity_logs', 'created_at')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasColumn('activity_logs', 'updated_at')) {
            Schema::table('activity_logs', function (Blueprint $table) {
                $table->timestamp('updated_at')->nullable();
            });
        }
    }

    public function down(): void
    {
        //
    }
};