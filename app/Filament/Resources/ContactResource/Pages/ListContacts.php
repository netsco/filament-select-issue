<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Enums\ContactType;
use App\Filament\Resources\ContactResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListContacts extends ListRecords
{
    protected static string $resource = ContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('Company')
                    ->icon('heroicon-o-building-office')
                    ->url(fn (): string => ContactResource::getUrl('create').'?type='.ContactType::Company->value),
                Actions\Action::make('Person')
                    ->icon('heroicon-o-user')
                    ->url(fn (): string => ContactResource::getUrl('create').'?type='.ContactType::Person->value),
            ])->label('Contact')
                ->icon('heroicon-o-plus')
                ->button(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => Tab::make(),
            'Companies' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('type', '=', ContactType::Company)),
            'People' => Tab::make()->modifyQueryUsing(fn (Builder $query) => $query->where('type', '=', ContactType::Person)),
        ];
    }
}
