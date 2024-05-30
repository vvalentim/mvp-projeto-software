<?php

namespace App\Filament\Concerns;

use App\Models\Customer;
use App\Models\FollowUp;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Arr;

trait EditableFollowUpModal
{

    protected function getRecordState(): array
    {
        $record = FollowUp::with([
            'estate.owners.person',
            'customer.person'
        ])
            ->find($this->editModalRecordId)
            ->toArray();

        return [
            'lead' => Arr::only($record, ['name', 'email', 'phone', 'subject', 'message']),
            'estate' => $record['estate'],
            'owners' => $record['estate']['owners'],
            'customer' => $record['customer'],
        ];
    }

    protected function getLeadTab(): Tab
    {
        return Tab::make('Lead')
            ->schema([
                Section::make('Informações do lead')
                    ->icon('heroicon-s-user')
                    ->schema([
                        TextEntry::make('lead.name')->label('Nome'),
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('lead.phone')
                                    ->label('Telefone')
                                    ->icon('heroicon-s-phone'),
                                TextEntry::make('lead.email')
                                    ->label('E-mail')
                                    ->icon('heroicon-s-envelope')
                            ]),
                        TextEntry::make('lead.subject')->label('Assunto'),
                        TextEntry::make('lead.message')->label('Mensagem'),
                    ])
                    ->compact()
                    ->id('lead-tab-section'),
                Actions::make([
                    Action::make('Adicionar dados do cliente')
                        ->button()
                ])
                    ->alignEnd()
            ]);
    }

    public function getCustomerTab(): Tab
    {
        return Tab::make('Cliente')
            ->schema([
                Section::make('Informações do cliente')
                    ->icon('heroicon-s-user')
                    ->schema([
                        TextEntry::make('customer.person.name')->label('Nome'),
                    ])
                    ->compact()
                    ->id('estate-tab-section'),
                Actions::make([
                    Action::make('Alterar dados do cliente')
                        ->button()
                        ->color('gray'),
                    Action::make('Gerar proposta')
                        ->button()
                ])
                    ->alignEnd()
            ]);
    }

    public function getEstateTab(): Tab
    {
        return Tab::make('Imóvel')
            ->schema([
                Section::make('Informações do imóvel de interesse')
                    ->icon('heroicon-s-home')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('estate.title')->label('Título'),
                                TextEntry::make('estate.type')->label('Tipo'),
                            ]),
                        TextEntry::make('estate.description')->label('Descrição'),
                    ])
                    ->compact()
                    ->id('estate-tab-section'),
                Actions::make([
                    Action::make('Alterar o imóvel de interesse')
                        ->button()
                        ->color('gray'),
                    Action::make('Agendar uma visita')
                        ->button()
                ])
                    ->alignEnd()
            ]);
    }

    public function getOwnersTab(): Tab
    {
        return Tab::make('Proprietários')
            ->schema([
                RepeatableEntry::make('owners')
                    ->hiddenLabel()
                    ->schema([
                        Section::make('Proprietário')
                            ->icon('heroicon-s-user')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextEntry::make('person.name')->label('Nome'),
                                        TextEntry::make('person.phone_1')->label('Telefone 1'),
                                        TextEntry::make('person.phone_2')->label('Telefone 2')
                                    ])
                            ])
                            ->compact()
                            ->collapsible(true)
                    ])
                    ->contained(false)
            ]);
    }

    public function getModalInfoList(Infolist $infolist): Infolist
    {
        $state = $this->getRecordState();
        $tabs = [
            $state['customer'] ? $this->getCustomerTab() : $this->getLeadTab(),
            $this->getEstateTab(),
            $this->getOwnersTab(),
        ];

        return $infolist
            ->state($state)
            ->schema([
                Tabs::make()
                    ->tabs($tabs)
                    ->contained(false),
            ]);
    }
}
