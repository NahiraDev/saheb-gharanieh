<?php

namespace App\Enums;

enum CategoryLayout: string
{
    /** Image-card grid — used for the drink sections. */
    case Grid = 'grid';

    /** Compact two-column list — used for the hookah flavour lists. */
    case List = 'list';

    public function label(): string
    {
        return match ($this) {
            self::Grid => 'شبکه‌ای (با تصویر)',
            self::List => 'لیستی',
        };
    }
}
