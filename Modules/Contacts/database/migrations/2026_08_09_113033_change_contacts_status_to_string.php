<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            ! Schema::hasColumn(
                'contacts',
                'status'
            )
        ) {
            return;
        }

        /*
         * برای دیتابیس‌های قدیمی که status
         * هنوز enum بوده است.
         *
         * در Fresh Install این ستون از قبل
         * string است و تغییر مجدد مشکلی ایجاد نمی‌کند.
         */

        Schema::table(
            'contacts',
            function (
                Blueprint $table
            ) {
                $table
                    ->string(
                        'status',
                        30
                    )
                    ->default('new')
                    ->change();
            }
        );
    }

    public function down(): void
    {
        if (
            ! Schema::hasColumn(
                'contacts',
                'status'
            )
        ) {
            return;
        }

        /*
         * وضعیت‌هایی که در enum قدیمی وجود نداشتند
         * ابتدا به مقادیر قدیمی تبدیل می‌شوند.
         */

        DB::table('contacts')
            ->whereIn(
                'status',
                [
                    'contacted',
                    'interested',
                    'follow_up',
                    'demo_sent',
                    'no_answer',
                ]
            )
            ->update([
                'status' =>
                    'active',
            ]);

        DB::table('contacts')
            ->where(
                'status',
                'rejected'
            )
            ->update([
                'status' =>
                    'inactive',
            ]);

        Schema::table(
            'contacts',
            function (
                Blueprint $table
            ) {
                $table
                    ->enum(
                        'status',
                        [
                            'new',
                            'active',
                            'inactive',
                            'customer',
                        ]
                    )
                    ->default('new')
                    ->change();
            }
        );
    }
};