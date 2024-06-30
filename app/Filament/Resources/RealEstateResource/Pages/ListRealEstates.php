<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRealEstates extends ListRecords
{
    protected static string $resource = RealEstateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
