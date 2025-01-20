<?php

namespace App\Enums;
use Filament\Support\Contracts\HasLabel;

enum JenisKelaminEnum: string implements HasLabel
{
    case LakiLaki = 'Laki-Laki';
    case Perempuan = 'Perempuan';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::LakiLaki => 'Laki-Laki',
            self::Perempuan => 'Perempuan',
        };
    }
}
