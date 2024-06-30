<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRealEstate extends EditRecord
{
    protected static string $resource = RealEstateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
