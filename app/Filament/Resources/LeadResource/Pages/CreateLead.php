<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\CreateRecord;

class CreateLead extends CreateRecord
{
    protected static string $resource = LeadResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = LeadStatus::Verified;

        return $data;
    }

    public function form(Form $form): Form
    {
        return $form->schema(LeadResource::getFormSchema());
    }
}
