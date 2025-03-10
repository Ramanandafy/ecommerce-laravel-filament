<?php

namespace App\Filament\Resources\CommandeResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AdresseRelationManager extends RelationManager
{
    protected static string $relationship = 'adresse';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                      ->label('Nom')
                      ->required()
                      ->maxLength(255),

                TextInput::make('last_name')
                      ->label('Prenom')
                      ->required()
                      ->maxLength(255),

                TextInput::make('phone')
                      ->label('Numero de telephone')
                      ->required()
                      ->tel()
                      ->maxLength(20),

                TextInput::make('city')
                      ->label('Ville')
                      ->required()
                      ->maxLength(255),

                TextInput::make('state')
                      ->label('Etat')
                      ->required()
                      ->maxLength(255),

                TextInput::make('zip_code')
                      ->label('Code postal')
                      ->required()
                      ->numeric()
                      ->maxLength(10),

                Textarea::make('street_adress')
                    ->label('Adresse de la rue')
                    ->required()
                    ->columnSpanFull()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_adress')
            ->columns([
              TextColumn::make('fullname')
                    ->label('Nom complet'),

              TextColumn::make('phone')
                    ->label('Numero de telephone'),


              TextColumn::make('city')
                    ->label('Ville'),

              TextColumn::make('state')
                    ->label('Etat'),

              TextColumn::make('zip_code')
                    ->label('Code postal'),

              TextColumn::make('street_adress')
                    ->label('code de la rue'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
