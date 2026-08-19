<?php

namespace App\Support;

use DateTimeInterface;
use Illuminate\Support\Carbon;

class Persian
{
    private const WESTERN = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

    private const EASTERN = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];

    /** Arabic-Indic digits: some phone keyboards send these instead of the Persian set. */
    private const ARABIC = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    private const JALALI_MONTHS = [
        'فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور',
        'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند',
    ];

    /** Convert every ASCII digit in a string to its Persian counterpart. */
    public static function digits(string|int|float|null $value): string
    {
        return str_replace(self::WESTERN, self::EASTERN, (string) $value);
    }

    /** Reverse of digits() — needed for machine-readable values such as tel: links. */
    public static function western(?string $value): string
    {
        return str_replace(
            [...self::EASTERN, ...self::ARABIC],
            [...self::WESTERN, ...self::WESTERN],
            (string) $value
        );
    }

    /** 185000 → "۱۸۵٬۰۰۰" (Persian digits, Persian thousands separator). */
    public static function number(int|float|null $value): string
    {
        if (is_null($value)) {
            return '';
        }

        return self::digits(number_format((float) $value, 0, '.', '٬'));
    }

    /** 185000 → "۱۸۵٬۰۰۰ تومان"; null → the placeholder passed in. */
    public static function price(int|float|null $value, string $placeholder = '—'): string
    {
        if (is_null($value)) {
            return self::digits($placeholder);
        }

        return self::number($value).' تومان';
    }

    /**
     * Read a price the way the owner types it — "۱۸۵٬۰۰۰", "185,000 تومان",
     * "۱۸۵ ۰۰۰" — and return plain Tomans. Blank input means "no price yet".
     */
    public static function amount(string|int|null $value): ?int
    {
        if (is_null($value) || trim((string) $value) === '') {
            return null;
        }

        $digits = preg_replace('/\D/', '', self::western((string) $value));

        return $digits === '' ? null : (int) $digits;
    }

    /**
     * Gregorian → Jalali (Solar Hijri) as [year, month, day].
     *
     * The standard integer-arithmetic conversion; no extension required.
     *
     * @return array{0: int, 1: int, 2: int}
     */
    public static function jalali(DateTimeInterface $date): array
    {
        $gy = (int) $date->format('Y');
        $gm = (int) $date->format('n');
        $gd = (int) $date->format('j');

        $monthDays = [0, 31, 59, 90, 120, 151, 181, 212, 243, 273, 304, 334];
        $leapBase = $gm > 2 ? $gy + 1 : $gy;

        $days = 355666 + (365 * $gy) + intdiv($leapBase + 3, 4) - intdiv($leapBase + 99, 100)
            + intdiv($leapBase + 399, 400) + $gd + $monthDays[$gm - 1];

        $jy = -1595 + (33 * intdiv($days, 12053));
        $days %= 12053;

        $jy += 4 * intdiv($days, 1461);
        $days %= 1461;

        if ($days > 365) {
            $jy += intdiv($days - 1, 365);
            $days = ($days - 1) % 365;
        }

        if ($days < 186) {
            $jm = 1 + intdiv($days, 31);
            $jd = 1 + ($days % 31);
        } else {
            $jm = 7 + intdiv($days - 186, 30);
            $jd = 1 + (($days - 186) % 30);
        }

        return [$jy, $jm, $jd];
    }

    /** "۲۶ مرداد ۱۴۰۵" in the app's timezone. */
    public static function date(DateTimeInterface|string|null $date): string
    {
        if (blank($date)) {
            return '—';
        }

        $local = Carbon::parse($date)->timezone(config('app.timezone'));
        [$year, $month, $day] = self::jalali($local);

        return self::digits($day).' '.self::JALALI_MONTHS[$month - 1].' '.self::digits($year);
    }

    /** "۲۶ مرداد ۱۴۰۵ — ۲۳:۰۵". */
    public static function dateTime(DateTimeInterface|string|null $date): string
    {
        if (blank($date)) {
            return '—';
        }

        $local = Carbon::parse($date)->timezone(config('app.timezone'));

        return self::date($local).' — '.self::digits($local->format('H:i'));
    }
}
