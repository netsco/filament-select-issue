<?php

namespace App\Enums;

use App\Enums\Traits\EnumToArray;
use App\Enums\Traits\FromGet;
use Filament\Support\Contracts\HasIcon;

enum ContactType: string implements HasIcon
{
    use EnumToArray, FromGet;

    case Person = 'person';
    case Company = 'company';

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Person => 'heroicon-s-user',
            self::Company => 'heroicon-s-building-office',
        };
    }
}
