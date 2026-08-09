<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'interactions',
            function (Blueprint $table) {
                $table->string(
                    'status_after_call',
                    30
                )
                    ->nullable()
                    ->after('result');
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'interactions',
            function (Blueprint $table) {
                $table->dropColumn(
                    'status_after_call'
                );
            }
        );
    }
};