<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use App\Models\RealEstate;
use Filament\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditRealEstate extends EditRecord
{
    protected static string $resource = RealEstateResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Group::make()
                            ->schema(RealEstateResource::getDetailsFormSchema()),

                        Section::make('Proprietários')
                            ->schema([
                                RealEstateResource::getOwnersRepeater(),
                            ])
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Imóvel cadastro em')
                                    ->content(fn (RealEstate $record): ?string => $record->created_at->translatedFormat('l, d \d\e F, Y')),

                                Placeholder::make('updated_at')
                                    ->label('Última modificação')
                                    ->content(fn (RealEstate $record): ?string => $record->updated_at?->diffForHumans()),
                            ])
                    ])
                    ->columnSpan(1)
            ])
            ->columns(3);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
