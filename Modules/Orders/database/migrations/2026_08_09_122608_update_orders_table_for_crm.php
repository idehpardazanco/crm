<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | product_name
        |--------------------------------------------------------------------------
        */

        if (
            Schema::hasColumn('orders', 'title') &&
            ! Schema::hasColumn('orders', 'product_name')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('title', 'product_name');
            });
        }

        if (
            ! Schema::hasColumn('orders', 'title') &&
            ! Schema::hasColumn('orders', 'product_name')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('product_name')->nullable();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | user_id
        |--------------------------------------------------------------------------
        */

        if (! Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }

        /*
        |--------------------------------------------------------------------------
        | تبدیل وضعیت‌های قدیمی
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('orders', 'status')) {
            DB::table('orders')
                ->where('status', 'pending')
                ->update([
                    'status' => 'new',
                ]);

            DB::table('orders')
                ->where('status', 'failed')
                ->update([
                    'status' => 'cancelled',
                ]);

            DB::table('orders')
                ->where('status', 'shipped')
                ->update([
                    'status' => 'completed',
                ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'status')) {
            DB::table('orders')
                ->where('status', 'new')
                ->update([
                    'status' => 'pending',
                ]);

            DB::table('orders')
                ->where('status', 'cancelled')
                ->update([
                    'status' => 'failed',
                ]);

            DB::table('orders')
                ->where('status', 'completed')
                ->update([
                    'status' => 'shipped',
                ]);
        }

        if (Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (
            Schema::hasColumn('orders', 'product_name') &&
            ! Schema::hasColumn('orders', 'title')
        ) {
            Schema::table('orders', function (Blueprint $table) {
                $table->renameColumn('product_name', 'title');
            });
        }
    }
};