<?php

namespace App\Support;

/**
 * How large a photo the panel may accept.
 *
 * The hint under the image field used to promise 6 MB while PHP's own
 * upload_max_filesize quietly capped uploads at 2 MB — so an ordinary phone photo
 * was refused, by a message that never mentioned size. The number is worked out
 * here instead, from what PHP will really take, so the hint, the validation rule
 * and the truth cannot drift apart again.
 *
 * Lifting the ceiling is a server job rather than a code one: see public/.user.ini
 * for the PHP side and the deployment notes in the README for nginx.
 */
class UploadLimit
{
    /** What the panel would like to allow, in kilobytes. */
    public const WANTED_KILOBYTES = 6144;

    /**
     * Held back from post_max_size for the rest of the form. The text fields and
     * the token are tiny, but of the two ways an upload can be too big this is
     * the worse one: a photo that clears upload_max_filesize and then overruns
     * post_max_size makes PHP discard the entire body, leaving nothing to render
     * an error from — not even the token that proves the form was ours.
     */
    private const FORM_HEADROOM_KILOBYTES = 256;

    /** The cap actually in force — never more than PHP is willing to accept. */
    public static function kilobytes(): int
    {
        $limits = [self::WANTED_KILOBYTES];

        if ($upload = self::iniKilobytes('upload_max_filesize')) {
            $limits[] = $upload;
        }

        if ($post = self::iniKilobytes('post_max_size')) {
            $limits[] = max(1, $post - self::FORM_HEADROOM_KILOBYTES);
        }

        return (int) min($limits);
    }

    /** The same cap in bytes, for the file input's own check in admin.js. */
    public static function bytes(): int
    {
        return self::kilobytes() * 1024;
    }

    /** The cap as the owner reads it: "۶" for the Persian hint and error text. */
    public static function megabytesLabel(): string
    {
        $megabytes = self::kilobytes() / 1024;

        // fmod rather than a comparison against floor(): PHP hands back an int from
        // that division when it comes out even, and 6 === 6.0 is false.
        $whole = fmod((float) $megabytes, 1.0) === 0.0;

        return Persian::digits(number_format(
            $megabytes,
            // "۶" rather than "۶٫۰" — but "۱٫۸" rather than a bare, wrong "۲".
            $whole ? 0 : 1,
            '٫',
            '',
        ));
    }

    /**
     * A php.ini byte shorthand ("8M", "512K", "1G") in kilobytes. Null means the
     * setting imposes no limit of its own, so it stays out of the comparison.
     */
    private static function iniKilobytes(string $key): ?int
    {
        $raw = trim((string) ini_get($key));

        if ($raw === '') {
            return null;
        }

        $bytes = (float) $raw * match (strtolower(substr($raw, -1))) {
            'g' => 1024 ** 3,
            'm' => 1024 ** 2,
            'k' => 1024,
            // A bare number is already bytes, which is how php.ini reads it too.
            default => 1,
        };

        // 0 is how post_max_size spells "no limit".
        return $bytes > 0 ? (int) floor($bytes / 1024) : null;
    }
}
