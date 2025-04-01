<?php

namespace App\Filament\Resources\ContactResource\Widgets;

use App\Enums\ContactType;
use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RelatedContacts extends BaseWidget
{
    public Contact $record;

    protected static ?string $heading = null;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Contact::query()
                    ->when($this->record->type == ContactType::Company, function ($query) {
                        $query->whereIn('id', $this->record->employees()->get('contact_id'));
                    })->when($this->record->type == ContactType::Person, function ($query) {
                        $query->whereIn('id', $this->record->companies()->get('company_contact_id'));
                    })
            )
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->icon(fn (Contact $record): string => $record->type->getIcon())
                        ->size(TextColumn\TextColumnSize::Large)
                        ->iconPosition(IconPosition::After),
                    TextColumn::make('primaryEmail.value')
                        ->suffix(fn (Contact $record): ?string => ($record->emails_count > 1 ? ' ('.$record->emails_count.')' : null)),
                    TextColumn::make('primaryTel.value')
                        ->suffix(fn (Contact $record): ?string => ($record->tels_count > 1 ? ' ('.$record->tels_count.')' : null))
                        ->iconColor('primary'),
                    TextColumn::make('primaryAddress.displayComma')
                        ->suffix(fn (Contact $record): ?string => ($record->addresses_count > 1 ? ' ('.$record->addresses_count.')' : null))
                        ->iconColor('primary'),
                    TextColumn::make('categories.value')
                        ->color('info')
                        ->badge(),
                ]),
            ])
            ->recordUrl(
                fn (Contact $record): string => ContactResource::getUrl('view', ['record' => $record])
            )
            ->heading(null)
            ->paginated(false)
            ->contentGrid(['md' => 2, 'xl' => '3', '2xl' => 4]);
    }
}
