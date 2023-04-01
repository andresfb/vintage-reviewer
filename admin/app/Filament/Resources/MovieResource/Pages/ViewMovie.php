<?php

namespace App\Filament\Resources\MovieResource\Pages;

use App\Filament\Resources\MovieResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMovie extends ViewRecord
{
    protected static string $resource = MovieResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
