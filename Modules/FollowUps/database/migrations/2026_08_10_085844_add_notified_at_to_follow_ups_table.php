<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table(
            'follow_ups',
            function (
                Blueprint $table
            ) {
                $table
                    ->timestamp(
                        'notified_at'
                    )
                    ->nullable()
                    ->index();
            }
        );
    }

    public function down(): void
    {
        Schema::table(
            'follow_ups',
            function (
                Blueprint $table
            ) {
                $table->dropColumn(
                    'notified_at'
                );
            }
        );
    }
};