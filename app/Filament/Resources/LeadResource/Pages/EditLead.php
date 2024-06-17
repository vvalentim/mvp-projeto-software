<?php

namespace App\Filament\Resources\LeadResource\Pages;

use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource;
use App\Models\Lead;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;

class EditLead extends EditRecord
{
    protected static string $resource = LeadResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make()
                            ->schema([
                                Select::make('user_id')
                                    ->label('Responsável')
                                    ->placeholder('Selecione um usuário responsável pelo lead')
                                    ->searchPrompt('Digite o nome do usuário para buscá-lo')
                                    ->relationship('user')
                                    ->searchable()
                                    ->getOptionLabelFromRecordUsing(fn (User $record) => $record->name)
                                    ->preload()
                                    ->required()
                            ])
                            ->visible(fn (Get $get) => $get('status') === LeadStatus::Assigned->value),

                        Section::make()
                            ->schema(LeadResource::getFormSchema()),
                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Status')
                            ->schema([
                                ToggleButtons::make('status')
                                    ->live()
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options(LeadStatus::class)
                            ]),

                        Section::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Negociação iniciada em')
                                    ->content(fn (Lead $record): ?string => $record->created_at->translatedFormat('l, d \d\e F, Y')),

                                Placeholder::make('updated_at')
                                    ->label('Última modificação')
                                    ->content(fn (Lead $record): ?string => $record->updated_at?->diffForHumans()),
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
