<?php

namespace Tests\Unit;

use App\Support\IranianMobile;
use PHPUnit\Framework\TestCase;

class IranianMobileTest extends TestCase
{
    public function test_normal_mobile_remains_unchanged(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '09121234567'
            )
        );
    }


    public function test_plus_98_mobile_is_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '+989121234567'
            )
        );
    }


    public function test_0098_mobile_is_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '00989121234567'
            )
        );
    }


    public function test_98_mobile_is_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '989121234567'
            )
        );
    }


    public function test_mobile_without_leading_zero_is_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '9121234567'
            )
        );
    }


    public function test_persian_digits_are_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '۰۹۱۲۱۲۳۴۵۶۷'
            )
        );
    }


    public function test_mobile_with_spaces_and_dashes_is_normalized(): void
    {
        $this->assertSame(
            '09121234567',
            IranianMobile::normalize(
                '0912-123-4567'
            )
        );
    }


    public function test_valid_mobile_matches_required_regex(): void
    {
        $mobile =
            IranianMobile::normalize(
                '+989121234567'
            );


        $this->assertSame(
            1,
            preg_match(
                IranianMobile::REGEX,
                $mobile
            )
        );
    }


    public function test_invalid_mobile_does_not_match_required_regex(): void
    {
        $this->assertSame(
            0,
            preg_match(
                IranianMobile::REGEX,
                '02112345678'
            )
        );
    }
}