<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\CommandeResource;
use App\Models\Commande;
use Filament\Forms;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use PhpParser\Node\Stmt\Label;

class CommandesRelationManager extends RelationManager
{
    protected static string $relationship = 'commandes';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                  //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                 ->Label('Id de commmande')
                 ->searchable(),

                 TextColumn::make('grand_total')
                 ->label('Total')
                 ->money('eur'),

                TextColumn::make('status')
                ->label('Statut')
                ->badge()
                ->color(fn(string $state): string=>match($state){
                    'new' => 'info',
                    'processing' => 'warning',
                    'shipped' => 'success',
                    'delivered' => 'success',
                    'Cancelled' => 'danger'
                })
                ->icon(fn(string $state): string=>match($state){
                    'new' => 'heroicon-m-sparkles',
                    'processing' => 'heroicon-m-arrow-path',
                    'shipped' => 'heroicon-m-truck',
                    'delivered' => 'heroicon-m-check-badge',
                    'Cancelled' => 'heroicon-m-x-circle'
                })
                ->sortable(),

                TextColumn::make('payment_method')
                ->label('Methode de payement')
                ->sortable()
                ->searchable(),

                TextColumn::make('payment_status')
                ->label('Statut de payement')
                ->sortable()
                ->searchable()
                ->badge(),

                TextColumn::make('created_at')
                ->label('Date de commande')
                ->dateTime()

            ])
            ->filters([
                //
            ])
            ->headerActions([
                //Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\Action::make('View Commande')
                ->url(fn (Commande $record): string =>CommandeResource::getUrl('view', ['record'=>$record]))
                ->color('info')
                ->icon('heroicon-o-eye'),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
