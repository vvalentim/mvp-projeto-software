<?php

namespace App\Filament\Resources\FollowUpResource\Pages;

use App\Enums\FollowUpStatus;
use App\Filament\Resources\FollowUpResource;
use App\Models\FollowUp;
use Filament\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\RawJs;

class EditFollowUp extends EditRecord
{
    protected static string $resource = FollowUpResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()
                    ->schema([
                        Section::make('Lead')
                            ->collapsible()
                            ->key('sectionLead')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nome')
                                    ->maxLength(100)
                                    ->required(),

                                TextInput::make('email')
                                    ->label('Endereço de e-mail')
                                    ->maxLength(255)
                                    ->email()
                                    ->required(),

                                TextInput::make('phone')
                                    ->label('Telefone ou celular')
                                    ->tel()
                                    ->mask(RawJs::make(<<<'JS'
                                        $input.length <= 14 ? '(99) 9999-9999' : '(99) 99999-9999'
                                    JS))
                                    ->maxLength(20)
                                    ->required(),

                                TextInput::make('subject')
                                    ->label('Assunto')
                                    ->maxLength(100),

                                Textarea::make('message')
                                    ->label('Mensagem')
                                    ->maxLength(500)
                                    ->columnSpan('full'),
                            ])
                            ->visible(fn (Get $get) => $get('status') === FollowUpStatus::Lead->value),

                        Section::make('Cliente interessado')
                            ->collapsible()
                            ->id('sectionCustomer')
                            ->schema([
                                FollowUpResource::getCustomerSelector(),
                                FollowUpResource::getCustomerGroup()
                            ])
                            ->hidden(fn (Get $get) => $get('status') === FollowUpStatus::Lead->value),

                        Section::make('Imóvel de interesse')
                            ->collapsible()
                            ->schema([
                                FollowUpResource::getEstateSelector(),
                                FollowUpResource::getEstateGroup(),
                            ]),


                    ])
                    ->columnSpan(2),

                Group::make()
                    ->schema([
                        Section::make('Etapa da negociação')
                            ->schema([
                                ToggleButtons::make('status')
                                    ->live()
                                    ->hiddenLabel()
                                    ->inline()
                                    ->options(FollowUpStatus::class)
                            ]),

                        Section::make()
                            ->schema([
                                Placeholder::make('created_at')
                                    ->label('Negociação iniciada em')
                                    ->content(fn (FollowUp $record): ?string => $record->created_at->translatedFormat('l, d \d\e F, Y')),

                                Placeholder::make('updated_at')
                                    ->label('Última modificação')
                                    ->content(fn (FollowUp $record): ?string => $record->updated_at?->diffForHumans()),
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
