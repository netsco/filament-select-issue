<?php

namespace App\Filament\Resources\ContactResource\Pages;

use App\Enums\ContactType;
use App\Filament\Resources\ContactResource;
use App\Models\Contact;
use Filament\Actions;
use Filament\Infolists\Components\KeyValueEntry;
use Filament\Infolists\Components\Livewire;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;

class ViewContact extends ViewRecord
{
    protected static string $resource = ContactResource::class;

    public function getTitle(): string|Htmlable
    {
        return $this->record->name;
    }

    public function getSubheading(): string|Htmlable|null
    {
        if ($this->record->type === ContactType::Person && $this->record->primaryCompany()->exists()) {
            return new HtmlString($this->record->primaryCompany->job_title.' @ <a href="'.ContactResource::getUrl('view', ['record' => $this->record->primaryCompany->contact]).'" class="underline">'.$this->record->primaryCompany->contact->name.'</a>');
        }

        return null;
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Tabs')
                    ->tabs([
                        Tabs\Tab::make('Details')
                            ->schema([
                                TextEntry::make('categories.value')
                                    ->badge()
                                    ->color(fn (Contact $record) => $record->contact->categories->color ?? 'info')
                                    ->columnSpan(['md' => 1, 'lg' => 3]),
                                RepeatableEntry::make('emails')
                                    ->schema([
                                        TextEntry::make('value')
                                            ->label(function (Contact\Email $record) {
                                                return new HtmlString($record->type?->value.(($record->is_primary) ? ' - <small>(Primary)</small>' : ''));
                                            })
                                            ->iconColor('primary')
                                            ->icon('heroicon-m-envelope')
                                            ->url(fn (Contact\Email $record): string => 'mailto:'.$record->value),
                                    ])
                                    ->placeholder('None'),
                                RepeatableEntry::make('tels')
                                    ->schema([
                                        PhoneEntry::make('value')
                                            ->label(function (Contact\Tel $record) {
                                                return new HtmlString($record->type?->value.(($record->is_primary) ? ' - <small>(Primary)</small>' : ''));
                                            })
                                            ->iconColor('primary')
                                            ->icon('heroicon-m-envelope')
                                            ->url(fn (Contact\Tel $record): string => 'tel:'.$record->value),
                                    ])
                                    ->placeholder('None'),
                                RepeatableEntry::make('addresses')
                                    ->schema([
                                        TextEntry::make('displayNl')
                                            ->label(function (Contact\Address $record) {
                                                return new HtmlString($record->type?->value.(($record->is_primary) ? ' - <small>(Primary)</small>' : ''));
                                            })
                                            ->iconColor('primary')
                                            ->icon('heroicon-m-clipboard')
                                            ->formatStateUsing(fn (string $state): Htmlable => new HtmlString(nl2br($state)))
                                            ->copyable()
                                            ->copyMessage('Copied!')
                                            ->copyMessageDuration(1500),
                                    ])
                                    ->placeholder('None'),
                                TextEntry::make('sources.value')->badge()
                                    ->columnSpan(['md' => 1, 'lg' => 3]),
                                RepeatableEntry::make('socialMedia')
                                    ->schema([
                                        TextEntry::make('value')
                                            ->label(function (Contact\SocialMedia $record) {
                                                return $record->type->value;
                                            }),
                                    ])
                                    ->placeholder('None'),
                                RepeatableEntry::make('websites')
                                    ->schema([
                                        TextEntry::make('value')
                                            ->label(function (Contact\Website $record) {
                                                return $record->type->value;
                                            })
                                            ->url(fn (Contact\Website $record): string => $record->value)
                                            ->openUrlInNewTab(),
                                    ])
                                    ->placeholder('None'),
                                RepeatableEntry::make('dates')
                                    ->schema([
                                        TextEntry::make('value')
                                            ->label(function (Contact\Date $record) {
                                                return $record->type->value;
                                            })
                                            ->date('d/m/Y'),
                                        TextEntry::make('notes'),
                                    ])
                                    ->columns(2)
                                    ->placeholder('None'),
                                KeyValueEntry::make('customFields.value')
                                    ->schema([
                                        TextEntry::make('value')
                                            ->label(function (Contact\CustomField $record) {
                                                return $record->type->value;
                                            }),
                                    ])
                                    ->hidden(fn (Contact $record): bool => empty($record->customFields))
                                    ->columnSpan(['md' => 1, 'lg' => 3])
                                    ->placeholder('None'),
                            ])->columns(['md' => 1, 'lg' => 3]),
                        Tabs\Tab::make('Related Contacts')
                            ->badge(fn (Contact $record) => $record->employees()->count() + $record->companies()->count())
                            ->schema([
                                Livewire::make(\App\Filament\Resources\ContactResource\Widgets\RelatedContacts::class),
                            ]),
                    ])->columns(1),
            ])->columns(1);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\EditAction::make()
                ->slideOver(),
        ];
    }
}
