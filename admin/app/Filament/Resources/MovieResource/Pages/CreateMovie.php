<?php

namespace App\Filament\Resources\MovieResource\Pages;

use App\Filament\Resources\MovieResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMovie extends CreateRecord
{
    protected static string $resource = MovieResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
