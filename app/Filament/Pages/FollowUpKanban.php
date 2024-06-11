<?php

namespace App\Filament\Pages;

use App\Enums\FollowUpStatus;
use App\Filament\Concerns\EditableFollowUpModal;
use App\Models\FollowUp;
use Filament\Actions\Action;
use Illuminate\Support\Collection;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class FollowUpKanban extends KanbanBoard
{
    use EditableFollowUpModal;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-view-columns';

    protected static ?string $title = 'Quadro de negociações';

    protected static ?string $slug = 'quadro-negociacoes';

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

    protected function getHeaderActions(): array
    {
        return [
            Action::make('create')
                ->label('Criar negociação')
                ->url('negociacoes/criar')
        ];
    }
}
