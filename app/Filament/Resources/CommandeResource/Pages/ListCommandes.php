<?php

namespace App\Filament\Resources\CommandeResource\Pages;

use App\Filament\Resources\CommandeResource;
use App\Filament\Resources\CommandeResource\Widgets\CommandeStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListCommandes extends ListRecords
{
    protected static string $resource = CommandeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array{
       return[
        CommandeStats::class
       ];
    }
    public function getTabs(): array
    {
        return[
            null => Tab::make('All'),
            'Nouveau' => Tab::make()->query(fn($query) => $query->where('status', 'new')),
            'Processus' => Tab::make()->query(fn($query) => $query->where('status', 'processing')),
            'Accepter' => Tab::make()->query(fn($query) => $query->where('status', 'shipped')),
            'Livrer' => Tab::make()->query(fn($query) => $query->where('status', 'delivered')),
            'Annuler' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled'))
        ];
    }
}
