<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement(
            "ALTER TABLE contacts
             MODIFY status VARCHAR(30)
             NOT NULL DEFAULT 'new'"
        );
    }

    public function down(): void
    {
        DB::table('contacts')
            ->whereIn('status', [
                'contacted',
                'interested',
                'follow_up',
                'demo_sent',
                'no_answer',
            ])
            ->update([
                'status' => 'active',
            ]);

        DB::table('contacts')
            ->where('status', 'rejected')
            ->update([
                'status' => 'inactive',
            ]);

        DB::statement(
            "ALTER TABLE contacts
             MODIFY status ENUM(
                'new',
                'active',
                'inactive',
                'customer'
             )
             NOT NULL DEFAULT 'new'"
        );
    }
};