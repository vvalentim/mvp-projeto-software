<?php

namespace App\Filament\Resources\RealEstateResource\Pages;

use App\Filament\Resources\RealEstateResource;
use Filament\Actions;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateRealEstate extends CreateRecord
{
    use HasWizard;

    protected static string $resource = RealEstateResource::class;

    protected static bool $canCreateAnother = false;

    public function form(Form $form): Form
    {
        return parent::form($form)
            ->schema([
                Wizard::make($this->getSteps())
                    ->startOnStep($this->getStartStep())
                    ->cancelAction($this->getCancelFormAction())
                    ->submitAction($this->getSubmitFormAction())
                    ->skippable($this->hasSkippableSteps())
                    ->contained(false),
            ])
            ->columns(null);
    }

    /** @return Step[] */
    protected function getSteps(): array
    {
        return [
            Step::make('Detalhes do imóvel')
                ->schema(RealEstateResource::getDetailsFormSchema()),

            Step::make('Proprietários')
                ->schema([
                    Section::make('Proprietários')
                        ->schema([
                            RealEstateResource::getOwnersRepeater()
                        ])
                ]),
        ];
    }
}
