<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\CommandeResource;
use App\Models\Commande;
use Filament\Actions\Action;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestCommandes extends BaseWidget
{
    protected int| string| array $columnSpan = 'full';
    protected static? int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(CommandeResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')

            ->columns([
                    TextColumn::make('id')
                     ->Label('Id de commmande')
                     ->searchable(),

                     TextColumn::make('user.name')
                     ->label('Utilisateur')
                     ->sortable()
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

            ->actions([
                Tables\Actions\Action::make('View Commande')
                   ->url(fn(Commande $record):string=>CommandeResource::getUrl('view',['record'=>$record]))
                   ->icon('heroicon-o-eye'),
            ]);
    }
}
