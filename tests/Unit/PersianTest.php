<?php

namespace Tests\Unit;

use App\Support\Persian;
use PHPUnit\Framework\TestCase;

class PersianTest extends TestCase
{
    public function test_it_converts_digits(): void
    {
        $this->assertSame('۱۴۰۴', Persian::digits(1404));
        $this->assertSame('۰۲۱-۱۲۳۴۵۶۷۸', Persian::digits('021-12345678'));
        $this->assertSame('', Persian::digits(null));
    }

    public function test_it_converts_digits_back_for_machine_readable_values(): void
    {
        $this->assertSame('02112345678', Persian::western('۰۲۱۱۲۳۴۵۶۷۸'));
    }

    public function test_it_formats_numbers_with_a_persian_separator(): void
    {
        $this->assertSame('۱۸۵٬۰۰۰', Persian::number(185000));
        $this->assertSame('', Persian::number(null));
    }

    public function test_it_formats_prices(): void
    {
        $this->assertSame('۱۸۵٬۰۰۰ تومان', Persian::price(185000));
    }

    public function test_a_missing_price_falls_back_to_a_placeholder(): void
    {
        $this->assertSame('—', Persian::price(null));
        $this->assertSame('قیمت روز', Persian::price(null, 'قیمت روز'));
    }
}
