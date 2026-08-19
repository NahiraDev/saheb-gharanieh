<?php

namespace App\Enums;

enum CategoryKind: string
{
    case Drink = 'drink';
    case Hookah = 'hookah';

    public function label(): string
    {
        return match ($this) {
            self::Drink => 'نوشیدنی',
            self::Hookah => 'قلیان',
        };
    }
}
