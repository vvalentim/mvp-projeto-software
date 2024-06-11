<?php

namespace App\Filament\Resources\FollowUpResource\Pages;

use App\Enums\FollowUpStatus;
use App\Filament\Resources\FollowUpResource;
use App\Models\Lead;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\CreateRecord\Concerns\HasWizard;

class CreateFollowUp extends CreateRecord
{
    use HasWizard;

    protected static string $resource = FollowUpResource::class;

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
                    ->contained(),
            ])
            ->columns('full');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        if (!empty($data['lead_id'])) {
            $leadFields = Lead::query()
                ->where('id', $data['lead_id'])
                ->firstOrFail(['name', 'email', 'phone', 'subject', 'message'])
                ->toArray();

            foreach ($leadFields as $key => $value) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    protected function getSteps(): array
    {
        return [
            Step::make('Especificar etapa da negociação')
                ->schema([
                    Select::make('status')
                        ->native(false)
                        ->options(FollowUpStatus::localizedFilterOptions())
                        ->required()
                ]),

            Step::make('Especificar associação de lead ou cliente')
                ->schema([
                    Select::make('lead_or_customer')
                        ->label('Tipo de associação')
                        ->native(false)
                        ->options([
                            'lead' => 'Lead',
                            'customer' => 'Cliente'
                        ])
                        ->default('lead')
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set) {
                            $set('lead_id', null);
                            $set('customer_id', null);
                        }),

                    Grid::make('full')
                        ->schema(fn (Get $get): array => match ($get('lead_or_customer')) {
                            'lead' => [
                                FollowUpResource::getLeadSelector(),
                                FollowUpResource::getLeadGroup(),
                            ],
                            'customer' => [
                                FollowUpResource::getCustomerSelector(),
                                FollowUpResource::getCustomerGroup()
                            ],
                            default => []
                        })
                        ->key('dynamicSelectorContent'),
                ]),

            Step::make('Especificar imóvel')
                ->schema([
                    FollowUpResource::getEstateSelector(),
                    FollowUpResource::getEstateGroup()
                ])
        ];
    }
}
