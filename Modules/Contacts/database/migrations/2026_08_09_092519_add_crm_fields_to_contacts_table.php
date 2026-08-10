<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Compatibility Migration
        |--------------------------------------------------------------------------
        |
        | این Migration برای دیتابیس‌هایی است که جدول contacts
        | را با نسخه قدیمی پروژه ساخته‌اند.
        |
        | در Fresh Install، Migration اصلی همه این ستون‌ها را دارد
        | و هیچ ستون تکراری اضافه نخواهد شد.
        |
        */

        $hasBusinessName =
            Schema::hasColumn(
                'contacts',
                'business_name'
            );

        $hasPhone =
            Schema::hasColumn(
                'contacts',
                'phone'
            );

        $hasCity =
            Schema::hasColumn(
                'contacts',
                'city'
            );

        $hasCategory =
            Schema::hasColumn(
                'contacts',
                'category'
            );

        $hasSource =
            Schema::hasColumn(
                'contacts',
                'source'
            );

        $hasStatus =
            Schema::hasColumn(
                'contacts',
                'status'
            );

        $hasAssignedUser =
            Schema::hasColumn(
                'contacts',
                'assigned_user_id'
            );

        $hasDescription =
            Schema::hasColumn(
                'contacts',
                'description'
            );


        Schema::table(
            'contacts',
            function (
                Blueprint $table
            ) use (
                $hasBusinessName,
                $hasPhone,
                $hasCity,
                $hasCategory,
                $hasSource,
                $hasStatus,
                $hasAssignedUser,
                $hasDescription
            ) {
                if (! $hasBusinessName) {
                    $table
                        ->string(
                            'business_name'
                        )
                        ->nullable();
                }

                if (! $hasPhone) {
                    $table
                        ->string(
                            'phone',
                            30
                        )
                        ->nullable();
                }

                if (! $hasCity) {
                    $table
                        ->string(
                            'city',
                            100
                        )
                        ->nullable();
                }

                if (! $hasCategory) {
                    $table
                        ->string(
                            'category',
                            100
                        )
                        ->nullable();
                }

                if (! $hasSource) {
                    $table
                        ->string(
                            'source',
                            100
                        )
                        ->nullable();
                }

                if (! $hasStatus) {
                    $table
                        ->string(
                            'status',
                            30
                        )
                        ->default('new')
                        ->index();
                }

                if (! $hasAssignedUser) {
                    $table
                        ->foreignId(
                            'assigned_user_id'
                        )
                        ->nullable()
                        ->constrained(
                            'users'
                        )
                        ->nullOnDelete();
                }

                if (! $hasDescription) {
                    $table
                        ->text(
                            'description'
                        )
                        ->nullable();
                }
            }
        );
    }

    public function down(): void
    {
        /*
         * عمداً خالی است.
         *
         * این Migration فقط برای سازگاری
         * دیتابیس‌های قدیمی نگه داشته شده است.
         *
         * حذف این ستون‌ها در rollback می‌تواند
         * ستون‌های اصلی جدول contacts را از بین ببرد.
         */
    }
};