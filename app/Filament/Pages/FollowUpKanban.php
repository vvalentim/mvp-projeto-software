<?php

namespace App\Filament\Pages;

use App\Enums\FollowUpStatus;
use App\Enums\LeadStatus;
use App\Filament\Concerns\EditableFollowUpModal;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\RealEstate;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class FollowUpKanban extends KanbanBoard
{
    use EditableFollowUpModal;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $title = 'Negociações';

    protected static string $model = FollowUp::class;

    protected static string $statusEnum = FollowUpStatus::class;

    protected static string $view = 'components.followups-kanban.board';

    protected static string $headerView = 'components.followups-kanban.header';

    protected static string $recordView = 'components.followups-kanban.record';

    protected static string $statusView = 'components.followups-kanban.status';

    protected string $editModalTitle = 'Negociação';

    protected string $editModalWidth = '3xl';

    protected string $editModalSaveButtonLabel = 'Editar';

    protected function records(): Collection
    {
        return $this->getEloquentQuery()
            ->where('user_id', auth()->id())
            ->when(method_exists(static::$model, 'scopeOrdered'), fn ($query) => $query->ordered())
            ->get();
    }

    protected function getRealEstateSelectComponent(): Select
    {
        return Select::make('real_estate_id')
            ->label('Imóvel')
            ->searchingMessage('Buscando...')
            ->noSearchResultsMessage('Nenhum imóvel encontrado.')
            ->searchable()
            ->relationship(
                name: 'estate',
                titleAttribute: 'title'
            )
            ->getOptionLabelFromRecordUsing(fn (RealEstate $estate) => $estate->getSearchLabel())
            ->required();
    }

    protected function getFormSchema(): array
    {
        return [
            $this->getRealEstateSelectComponent(),
            TextInput::make('name')
                ->required()
                ->maxLength(100)
                ->label('Nome'),
            TextInput::make('email')
                ->required()
                ->label('Email'),
            TextInput::make('phone')
                ->required()
                ->maxLength(20)
                ->mask(RawJs::make(<<<'JS'
                    $input.length <= 14 ? '(99) 9999-9999' : '(99) 99999-9999'
                JS))
                ->label('Telefone'),
            TextInput::make('subject')
                ->label('Assunto')
                ->maxLength(100),
            Textarea::make('message')
                ->label('Mensagem')
                ->maxLength(500)
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('2xl')
                ->label('Novo Lead')
                ->model(Lead::class)
                ->modalHeading('Novo Lead')
                ->form($this->getFormSchema())
                ->mutateFormDataUsing(fn (array $data): array => [
                    ...$data,
                    'status' => LeadStatus::Assigned,
                    'user_id' => auth()->id(),
                ])
                ->after(function (array $data) {
                    FollowUp::factory()
                        ->create([
                            ...Arr::except($data, ['id', 'status']),
                            'status' => FollowUpStatus::Lead,
                        ]);
                })
                ->failureNotificationTitle('Não foi possível criar um novo lead')
                ->successNotificationTitle('Lead criado com sucesso')
                ->createAnother(false)
        ];
    }
}
