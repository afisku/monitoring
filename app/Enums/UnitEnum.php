<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum UnitEnum: string implements HasLabel
{
    case tkit = 'TK IT';
    case sdit = 'SD IT';
    case smpit = 'SMP IT';
    case smait = 'SMA IT';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::tkit => 'TK IT',
            self::sdit => 'SD IT',
            self::smpit => 'SMP IT',
            self::smait => 'SMA IT',
        };
    }
}




