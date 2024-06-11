<?php

namespace App\Filament\Concerns;

use App\Enums\FollowUpStatus;
use App\Enums\MaritalStatus;
use App\Models\FollowUp;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Tabs;
use Filament\Infolists\Components\Tabs\Tab;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Illuminate\Support\Arr;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Filament\Notifications\Notification;

trait EditableFollowUpModal
{
    protected ?FollowUp $editModalCurrentRecord = null;

    protected function getCurrentRecord(): ?FollowUp
    {
        if (
            empty($this->editModalCurrentRecord) ||
            $this->editModalRecordId != $this->editModalCurrentRecord->id
        ) {
            $this->editModalCurrentRecord = FollowUp::query()
                ->with(['estate.owners.person', 'customer.person'])
                ->find($this->editModalRecordId);
        }

        return $this->editModalCurrentRecord;
    }

    protected function getRecordState(): array
    {
        $state = [];

        if ($this->getCurrentRecord()) {
            $recordArr = $this->getCurrentRecord()->toArray();

            $state = Arr::only($recordArr, ['status', 'customer']);
            $state['lead'] = Arr::only($recordArr, ['name', 'email', 'phone', 'subject', 'message']);
            $state['estate'] = Arr::except($recordArr['estate'], 'owners');
            $state['owners'] = $recordArr['estate']['owners'];
        }

        return $state;
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
                    Action::make('Vincular cadastro do cliente')
                        ->button()
                ])
                    ->alignEnd()
            ]);
    }

    public function getCustomerTab(): Tab
    {
        $record = $this->getCurrentRecord();

        return Tab::make('Cliente')
            ->schema([
                Section::make('Informações do cliente')
                    ->icon('heroicon-s-user')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextEntry::make('customer.person.name')
                                    ->label('Nome'),

                                TextEntry::make('customer.person.num_registry')
                                    ->label(fn ($state) => strlen($state) > 14 ? 'CNPJ' : 'CPF'),

                                TextEntry::make('customer.person.phone_1')
                                    ->label('Telefone 1'),

                                TextEntry::make('customer.person.phone_2')
                                    ->label('Telefone 2'),

                                Group::make()
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('customer.filiation_mother')
                                                    ->label('Filiação mãe'),

                                                TextEntry::make('customer.filiation_father')
                                                    ->label('Filiação pai'),

                                                TextEntry::make('customer.marital_status')
                                                    ->label('Estado civil')
                                                    ->formatStateUsing(fn (string $state) => MaritalStatus::tryFrom($state)),

                                                TextEntry::make('customer.profession')
                                                    ->label('Profissão'),
                                            ]),
                                    ])
                                    ->columnSpanFull()
                            ])

                    ])
                    ->compact()
                    ->id('estate-tab-section'),

                Actions::make([
                    Action::make('Alterar cadastro do cliente')
                        ->button()
                        ->color('gray'),

                    Action::make('Gerar proposta')
                        ->visible($record->status === FollowUpStatus::Opportunity)
                        ->action('createPdf')
                        ->button(),
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

    public function createPdf()
    {
        $record = $this->getCurrentRecord();

        $data = [
            'nomeCliente' => $record->customer->person->name,
            'estadoCivil' => $record->customer->marital_status->getLabel(),
        ];

        $data = mb_convert_encoding($data, 'UTF-8');

        $pdf = PDF::loadView('pdf_template', $data);

        return response()
            ->streamDownload(function () use ($pdf) {
                echo $pdf->stream();
            }, 'proposta.pdf');
    }
}
