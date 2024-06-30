<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateRealEstate extends CreateRecord
{
    protected static string $resource = RealEstateResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([])
            ]);
    }
}
