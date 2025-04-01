<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Enums\ContactType;
use App\Filament\Resources\ContactResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\MaxWidth;

class CreateContact extends CreateRecord
{
    protected static string $resource = ContactResource::class;

    protected ?string $maxContentWidth = MaxWidth::FiveExtraLarge->value;

}
