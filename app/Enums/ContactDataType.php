<?php

namespace App\Enums;

use App\Models\Contact\Address;
use App\Models\Contact\Category;
use App\Models\Contact\CustomField;
use App\Models\Contact\Date;
use App\Models\Contact\Email;
use App\Models\Contact\SocialMedia;
use App\Models\Contact\Source;
use App\Models\Contact\Tel;
use App\Models\Contact\Website;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ContactDataType: string implements HasIcon, HasLabel
{
    case TYPE_ADDRESS = Address::class;
    case TYPE_CATEGORY = Category::class;
    case TYPE_CUSTOM_FIELD = CustomField::class;
    case TYPE_DATE = Date::class;
    case TYPE_EMAIL = Email::class;
    case TYPE_SOCIAL_MEDIA = SocialMedia::class;
    case TYPE_SOURCE = Source::class;
    case TYPE_TEL = Tel::class;
    case TYPE_WEBSITE = Website::class;

    public function getLabel(): ?string
    {
        return match ($this) {
            self::TYPE_ADDRESS => 'Address',
            self::TYPE_CATEGORY => 'Category',
            self::TYPE_CUSTOM_FIELD => 'Custom Field',
            self::TYPE_DATE => 'Date',
            self::TYPE_EMAIL => 'Email',
            self::TYPE_SOCIAL_MEDIA => 'Social Media',
            self::TYPE_SOURCE => 'Source',
            self::TYPE_TEL => 'Tel',
            self::TYPE_WEBSITE => 'Website',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TYPE_ADDRESS => 'heroicon-s-map-pin',
            self::TYPE_CATEGORY => 'heroicon-s-tag',
            self::TYPE_CUSTOM_FIELD => 'heroicon-s-table-cells',
            self::TYPE_DATE => 'heroicon-s-calendar-days',
            self::TYPE_EMAIL => 'heroicon-s-envelope',
            self::TYPE_SOCIAL_MEDIA => 'heroicon-s-share',
            self::TYPE_SOURCE => 'heroicon-s-tag',
            self::TYPE_TEL => 'heroicon-s-phone',
            self::TYPE_WEBSITE => 'heroicon-s-globe-alt',
        };
    }

    public function hasColour(): bool
    {
        return match ($this) {
            default => true
        };
    }
}
