<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarqueResource\Pages;
use App\Filament\Resources\MarqueResource\RelationManagers;
use App\Models\Marque;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class MarqueResource extends Resource
{
    protected static ?string $model = Marque::class;

    protected static ?string $navigationIcon = 'heroicon-o-computer-desktop';
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make([
                    Grid::make()
                      ->schema([
                           TextInput::make('name')
                               ->required()
                               ->maxLength(255)
                               ->live(onBlur:true)
                               ->afterStateUpdated(fn (string $operation, $state, Set $set)=> $operation
                               === 'create' ? $set('slug', Str::slug($state)) : null),


                           TextInput::make('slug')
                               ->maxLength(255)
                               ->disabled()
                               ->required()
                               ->dehydrated()
                               ->unique(Marque::class, 'slug', ignoreRecord:true)
                      ]),
                          FileUpload::make('image')
                               ->image()
                               ->directory('marques'),

                          Toggle::make('is_active')
                               ->required()
                               ->default(true)

              ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
               ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarques::route('/'),
            'create' => Pages\CreateMarque::route('/create'),
            'edit' => Pages\EditMarque::route('/{record}/edit'),
        ];
    }
}
