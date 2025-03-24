<?php

namespace App\Filament\Resources\CommandeResource\Widgets;

use App\Models\Commande;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class CommandeStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Nouveau Commandes', Commande::query()->where('status', 'new')->count()),
            Stat::make('Processus Commandes', Commande::query()->where('status', 'processing')->count()),
            Stat::make('Commande accepter', Commande::query()->where('status', 'shipped')->count()),
           // Stat::make('Prix moyenne', Number::currency(Commande::query()->avg('grand_total'), 'eur'))

        ];
    }
}
