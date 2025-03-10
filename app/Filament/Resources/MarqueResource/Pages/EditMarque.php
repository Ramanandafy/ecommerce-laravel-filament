<?php

namespace App\Filament\Resources\MarqueResource\Pages;

use App\Filament\Resources\MarqueResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarque extends EditRecord
{
    protected static string $resource = MarqueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
