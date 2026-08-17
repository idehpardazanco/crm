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
        | CONTACTS
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('contacts')) {

            if (! Schema::hasColumn('contacts', 'name')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table
                        ->string('name')
                        ->nullable()
                        ->after('business_name');
                });
            }

            if (! Schema::hasColumn('contacts', 'mobile')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table
                        ->string('mobile', 20)
                        ->nullable()
                        ->after('name');
                });
            }

            if (! Schema::hasColumn('contacts', 'email')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table
                        ->string('email')
                        ->nullable()
                        ->after('phone');
                });
            }

            if (! Schema::hasColumn('contacts', 'address')) {
                Schema::table('contacts', function (Blueprint $table) {
                    $table
                        ->text('address')
                        ->nullable()
                        ->after('assigned_user_id');
                });
            }

            /*
             * برای رکوردهای قدیمی:
             * اگر name خالی باشد business_name استفاده شود.
             */
            DB::table('contacts')
                ->whereNull('name')
                ->update([
                    'name' => DB::raw(
                        "COALESCE(business_name, 'مخاطب بدون نام')"
                    ),
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ORDERS
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('orders')) {

            if (! Schema::hasColumn('orders', 'contact_id')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table
                        ->unsignedBigInteger('contact_id')
                        ->nullable()
                        ->after('id');
                });
            }

            if (! Schema::hasColumn('orders', 'amount')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table
                        ->decimal('amount', 12, 2)
                        ->default(0)
                        ->after('product_name');
                });
            }

            if (! Schema::hasColumn('orders', 'status')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table
                        ->string('status', 30)
                        ->default('new')
                        ->after('amount');
                });
            }

            if (! Schema::hasColumn('orders', 'description')) {
                Schema::table('orders', function (Blueprint $table) {
                    $table
                        ->text('description')
                        ->nullable()
                        ->after('status');
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SMS LOGS
        |--------------------------------------------------------------------------
        */

        if (Schema::hasTable('sms_logs')) {

            if (! Schema::hasColumn('sms_logs', 'response_code')) {
                Schema::table('sms_logs', function (Blueprint $table) {
                    $table
                        ->string('response_code')
                        ->nullable()
                        ->after('error_message');
                });
            }

            if (! Schema::hasColumn('sms_logs', 'cost')) {
                Schema::table('sms_logs', function (Blueprint $table) {
                    $table
                        ->decimal('cost', 10, 2)
                        ->nullable()
                        ->after('response_code');
                });
            }
        }
    }

    public function down(): void
    {
        /*
         * عمداً خالی است.
         *
         * این Migration برای همگام‌سازی دیتابیس موجود
         * با ساختار فعلی CRM است و در Rollback
         * نباید اطلاعات موجود حذف شوند.
         */
    }
};