<?php

namespace App\Filament\Resources;

use App\Enums\ContactDataType;
use App\Enums\ContactType;
use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\RelationshipConstraint\Operators\IsRelatedToOperator;
use Filament\Tables\Filters\QueryBuilder\Constraints\SelectConstraint;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;
use Ysfkaya\FilamentPhoneInput\Tables\PhoneColumn;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?int $navigationSort = 2;

    public static function getGlobalSearchResultTitle(Contact|Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $rtn = [];
        if ($record->primaryEmail) {
            $rtn['Email'] = $record->primaryEmail->value;
        }
        if ($record->primaryTel) {
            $rtn['Tel'] = $record->primaryTel->value;
        }

        return $rtn;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['company', 'first_name', 'last_name', 'emails.value', 'tels.value'];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['emails', 'primaryEmail', 'primaryTel']);
    }

    public static function getGlobalSearchResultUrl(Model $record): ?string
    {
        return ContactResource::getUrl('view', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'lg' => 1, 'xl' => 3,
            ])
            ->schema(self::formSchema());
    }

    public static function formSchema(): array
    {
        return [
            Section::make([
                Select::make('type')->options(ContactType::class)->required()->reactive(),
            ])->columnSpan(1),
            Section::make([
                Select::make('categories')
                    ->relationship(titleAttribute: 'value')
                    ->multiple()
                    ->preload()
                    ->searchable(false)
                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                    ->createOptionForm([
                        Hidden::make('model')->default(Contact\Category::class),
                        TextInput::make('value')
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                return $rule->where('model', Contact\Category::class);
                            })
                            ->required(),
                        ColorPicker::make('colour'),
                    ])->minItems(1)->required(),
                Select::make('sources')
                    ->relationship(titleAttribute: 'value')
                    ->multiple()
                    ->preload()
                    ->searchable(false)
                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                    ->createOptionForm([
                        Hidden::make('model')->default(Contact\Source::class),
                        TextInput::make('value')
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                return $rule->where('model', Contact\Source::class);
                            })
                            ->required(),
                        ColorPicker::make('colour'),
                    ]),
            ])
                ->columns(2)
                ->columnSpan(2),
            Section::make()->schema([
                TextInput::make('company')
                    ->required()
                    ->hidden(fn(Get $get) => ContactType::fromGet($get('type')) !== ContactType::Company),
                Fieldset::make('Name')->schema([
                    TextInput::make('title')->required(),
                    TextInput::make('first_name')
                        ->columnSpan(2)
                        ->required(),
                    TextInput::make('last_name')
                        ->columnSpan(2)
                        ->required(),
                ])
                    ->columns(5)
                    ->hidden(fn(Get $get) => ContactType::fromGet($get('type')) !== ContactType::Person),
                Repeater::make('companies')
                    ->label('Companies')
                    ->relationship('companies')
                    ->schema([
                        Select::make('company_contact_id')->relationship(name: 'contact', titleAttribute: 'company')->label('Company')->searchable()->disableOptionsWhenSelectedInSiblingRepeaterItems()->required()->columnSpan(2),
                        TextInput::make('job_title')->required()->columnSpan(2),
                        Toggle::make('is_primary')->label('Primary')->inline(false)->fixIndistinctState()->columnSpan(1),
                    ])
                    ->columns(5)
                    ->addActionLabel('Add Company')
                    ->defaultItems(0)
                    ->hidden(fn(Get $get) => ContactType::fromGet($get('type')) !== ContactType::Person),
            ])->hidden(fn(Get $get) => !$get('type'))
                ->columns(1)
                ->columnSpan(3),
            Section::make()->schema([
                Textarea::make('background_info'),
            ]),
            Tabs::make('Tabs')->tabs([
                Tabs\Tab::make('Emails')
                    ->icon(ContactDataType::TYPE_EMAIL->getIcon())
                    ->schema([
                        Repeater::make('emails')->relationship('emails')->schema([
                            Select::make('type_id')
                                ->native(false)
                                ->relationship('type', 'value')
                                ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                ->createOptionForm([
                                    Hidden::make('model')->default(ContactDataType::TYPE_EMAIL),
                                    TextInput::make('value')
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                            return $rule->where('model', ContactDataType::TYPE_EMAIL);
                                        })
                                        ->required(),
                                    ColorPicker::make('colour'),
                                ])
                                ->required()->columnSpan(2),
                            TextInput::make('value')
                                ->distinct()
                                ->unique(ignoreRecord: true)
                                ->label('Email')
                                ->email()
                                ->required()
                                ->columnSpan(3),
                            Toggle::make('is_primary')->label('Primary')
                                ->inline(false)
                                ->columnSpan(1)
                                ->fixIndistinctState(),
                        ])->columns(6)
                            ->addActionLabel('Add Email')
                            ->defaultItems(0)
                            ->hiddenLabel(true),
                    ])->columns(1),
                Tabs\Tab::make('Addresses')
                    ->icon(ContactDataType::TYPE_ADDRESS->getIcon())
                    ->schema([
                        Repeater::make('addresses')->relationship('addresses')->schema([
                            Select::make('type_id')
                                ->native(false)
                                ->relationship('type', 'value')
                                ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                ->createOptionForm([
                                    Hidden::make('model')->default(ContactDataType::TYPE_ADDRESS),
                                    TextInput::make('value')
                                        ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                            return $rule->where('model', ContactDataType::TYPE_ADDRESS);
                                        })
                                        ->required(),
                                    ColorPicker::make('colour'),
                                ])
                                ->required(),
                            Toggle::make('is_primary')->label('Primary')->inline(false)->fixIndistinctState(),
                            Textarea::make('address')
                                ->required()
                                ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Get $get) {
                                    return $rule->where('city', $get('city'))
                                        ->where('region', $get('region'))
                                        ->where('postcode', $get('postcode'));
                                })
                                ->columnSpan(2)
                                ->hiddenLabel(true)
                                ->placeholder('Address'),
                            Fieldset::make('')->schema([
                                TextInput::make('city')
                                    ->columnSpan(2)->hiddenLabel(true)->placeholder('City'),
                                TextInput::make('region')
                                    ->columnSpan(2)->hiddenLabel(true)->placeholder('Region'),
                                TextInput::make('postcode')
                                    ->columnSpan(1)->hiddenLabel(true)->placeholder('Postcode'),
                            ])->columns(5)->columnSpan(2),
                        ])->addActionLabel('Add Address')->hiddenLabel(true)->defaultItems(0),
                    ])->columns(1),
                Tabs\Tab::make('Telephone')
                    ->icon(ContactDataType::TYPE_TEL->getIcon())
                    ->schema([
                        Repeater::make('tels')->relationship('tels')
                            ->schema([
                                Select::make('type_id')
                                    ->native(false)
                                    ->relationship('type', 'value')
                                    ->required()
                                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                    ->createOptionForm([
                                        Hidden::make('model')->default(ContactDataType::TYPE_TEL),
                                        TextInput::make('value')
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->where('model', ContactDataType::TYPE_TEL);
                                            })
                                            ->required(),
                                        ColorPicker::make('colour'),
                                    ])
                                    ->columnSpan(2),
                                PhoneInput::make('value')
                                    ->unique(ignoreRecord: true)
                                    ->label('Telephone')
                                    ->required()
                                    ->columnSpan(3),
                                Toggle::make('is_primary')
                                    ->label('Primary')
                                    ->inline(false)
                                    ->columnSpan(1)->fixIndistinctState(),
                            ])
                            ->columns(6)
                            ->addActionLabel('Add Telephone')
                            ->hiddenLabel(true)
                            ->defaultItems(0),
                    ])->columns(1),
                Tabs\Tab::make('Social Media')
                    ->icon(ContactDataType::TYPE_SOCIAL_MEDIA->getIcon())
                    ->schema([
                        Repeater::make('socialMedia')
                            ->relationship('socialMedia')
                            ->schema([
                                Select::make('type_id')
                                    ->native(false)
                                    ->relationship('type', 'value')
                                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                    ->createOptionForm([
                                        Hidden::make('model')->default(ContactDataType::TYPE_SOCIAL_MEDIA),
                                        TextInput::make('value')
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->where('model', ContactDataType::TYPE_SOCIAL_MEDIA);
                                            })
                                            ->required(),
                                        ColorPicker::make('colour'),
                                    ])
                                    ->columnSpan(1),
                                TextInput::make('value')->label('URL')->url()->required()->columnSpan(3),
                            ])->columns(4)->defaultItems(0)->addActionLabel('Add Social Media')->hiddenLabel(true),
                    ])->columns(1),
                Tabs\Tab::make('Websites')
                    ->icon(ContactDataType::TYPE_WEBSITE->getIcon())
                    ->schema([
                        Repeater::make('websites')
                            ->relationship('websites')
                            ->schema([
                                Select::make('type_id')
                                    ->native(false)
                                    ->relationship('type', 'value')
                                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                    ->createOptionForm([
                                        Hidden::make('model')->default(ContactDataType::TYPE_WEBSITE::class),
                                        TextInput::make('value')
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->where('model', ContactDataType::TYPE_WEBSITE);
                                            })
                                            ->required(),
                                        ColorPicker::make('colour'),
                                    ])
                                    ->columnSpan(1),
                                TextInput::make('value')->label('URL')->url()->required()->columnSpan(3),
                            ])->columns(4)->defaultItems(0)->addActionLabel('Add Website')->hiddenLabel(),
                    ])->columns(1),
                Tabs\Tab::make('Dates')
                    ->icon(ContactDataType::TYPE_DATE->getIcon())
                    ->schema([
                        Repeater::make('dates')
                            ->relationship('dates')
                            ->schema([
                                Select::make('type_id')
                                    ->native(false)
                                    ->relationship('type', 'value')
                                    ->createOptionAction(fn(Action $action) => $action->modalWidth(MaxWidth::Small))
                                    ->createOptionForm([
                                        Hidden::make('model')->default(ContactDataType::TYPE_DATE),
                                        TextInput::make('value')
                                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule) {
                                                return $rule->where('model', Contact\Date::class);
                                            })
                                            ->required(),
                                        ColorPicker::make('colour'),
                                    ])
                                    ->required()->columnSpan(1),
                                DatePicker::make('value')->label('Date'),
                                TextInput::make('notes')->required(),
                            ])->columns(3)->addActionLabel('Add Date')->defaultItems(0)->hiddenLabel(true),
                    ])->columns(1),
                Tabs\Tab::make('Custom Fields')
                    ->icon(ContactDataType::TYPE_CUSTOM_FIELD->getIcon())
                    ->schema([
                        Repeater::make('Custom Fields')
                            ->relationship('customFields')
                            ->schema([
                                Select::make('type_id')
                                    ->relationship('type', 'value')
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->required()->columnSpan(1),
                                TextInput::make('value')->required()->columnSpan(2),
                            ])->columns(3)
                            ->addActionLabel('Add Custom Field')
                            ->defaultItems(0)
                            ->hiddenLabel(true),
                    ])->columns(1),
            ])
                ->columns(1)
                ->columnSpan(3),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Stack::make([
                    TextColumn::make('name')
                        ->iconColor('gray')
                        ->icon(fn(Contact $record): string => $record->type->getIcon())
                        ->size(TextColumn\TextColumnSize::Large),
                    TextColumn::make('primaryEmail.value')
                        ->icon(ContactDataType::TYPE_EMAIL->getIcon())
                        ->iconColor('gray')
                        ->url(fn(Contact $record): string => 'mailto:' . $record->primaryEmail?->value),
                    PhoneColumn::make('primaryTel.value')
                        ->icon(ContactDataType::TYPE_TEL->getIcon())
                        ->iconColor('gray')
                        ->url(fn(Contact $record): string => 'tel:' . $record->primaryTel?->value),
                    TextColumn::make('primaryAddress.displayComma')
                        ->icon(ContactDataType::TYPE_ADDRESS->getIcon())
                        ->copyable()
                        ->copyMessage('Address copied to clipboard')
                        ->copyableState(fn(Contact $record): string => $record->primaryAddress->display_nl)
                        ->iconColor('gray'),
                    TextColumn::make('categories.value')
                        ->color('gray')
                        ->badge()
                        ->extraAttributes(['class' => 'mt-2']),
                ]),
            ])
            ->defaultPaginationPageOption(50)
            ->filters([
                QueryBuilder::make()
                    ->constraintPickerColumns(2)
                    ->constraints([
                        SelectConstraint::make('type')
                            ->options(ContactType::class),
                        TextConstraint::make('company')->icon('heroicon-o-building-office'),
                        TextConstraint::make('first_name')->icon('heroicon-o-user'),
                        TextConstraint::make('last_name')->icon('heroicon-o-user'),
                        TextConstraint::make('emails.value')->icon('heroicon-o-envelope'),
                        TextConstraint::make('tels.value')->icon('heroicon-o-phone'),
                        TextConstraint::make('addresses.address')->label('Address (Address)'),
                        TextConstraint::make('addresses.city')->label('Address (City)'),
                        TextConstraint::make('addresses.region')->label('Address (Region)'),
                        TextConstraint::make('addresses.postcode')->label('Address (Postcode)'),
                        RelationshipConstraint::make('categories')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('value')
                                    ->multiple()
                                    ->preload(),
                            )
                            ->icon('heroicon-o-tag'),
                        RelationshipConstraint::make('source')
                            ->multiple()
                            ->selectable(
                                IsRelatedToOperator::make()
                                    ->titleAttribute('value')
                                    ->multiple()
                                    ->preload(),
                            ),
                    ]),
            ], layout: FiltersLayout::Modal)
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
            ->deferFilters()
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                ])->extraAttributes(['class' => 'events-contact-list-ag'])->size(ActionSize::Small),
            ])
            ->headerActions([

                ]
            )
            ->bulkActions([

            ])
            ->contentGrid(['md' => 2, 'xl' => '3', '2xl' => 4])
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContacts::route('/'),
            'create' => Pages\CreateContact::route('/create'),
            'edit' => Pages\EditContact::route('/{record}/edit'),
            'view' => Pages\ViewContact::route('/{record}'),
        ];
    }

}
