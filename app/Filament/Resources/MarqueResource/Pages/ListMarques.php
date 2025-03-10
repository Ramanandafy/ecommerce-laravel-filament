<?php

namespace App\Filament\Resources\MarqueResource\Pages;

use App\Filament\Resources\MarqueResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarques extends ListRecords
{
    protected static string $resource = MarqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
