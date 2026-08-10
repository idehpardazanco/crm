<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        /*
        |--------------------------------------------------------------------------
        | Disable Vite During Tests
        |--------------------------------------------------------------------------
        |
        | تست‌های Backend نباید برای اجرا به npm run build
        | یا فایل manifest مربوط به Vite وابسته باشند.
        |
        */

        $this->withoutVite();
    }
}