<?php

namespace App\Filament\Resources;

use App\Enums\ContactDataType;
use App\Enums\ContactType;
use App\Filament\Resources\ContactResource\Pages;
use App\Models\Contact;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Hidden::make('type')->formatStateUsing(fn() => ContactType::Company->value),
                TextInput::make('company')
                    ->label('Company Name')
                    ->required(),
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
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('primaryEmail.value'),
            ])
            ->defaultPaginationPageOption(50)
            ->filters([
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->headerActions([

                ]
            )
            ->bulkActions([

            ]);
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
