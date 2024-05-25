<?php

namespace App\Filament\Pages;

use App\Enums\FollowUpStatus;
use App\Enums\LeadStatus;
use App\Models\FollowUp;
use App\Models\Lead;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Support\RawJs;
use Illuminate\Support\Collection;
use Mokhosh\FilamentKanban\Pages\KanbanBoard;

class FollowUpKanban extends KanbanBoard
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $title = 'Follow Ups';

    protected static string $model = FollowUp::class;

    protected static string $statusEnum = FollowUpStatus::class;

    protected static string $headerView = 'followups-kanban.header';

    protected static string $recordView = 'followups-kanban.record';

    protected static string $statusView = 'followups-kanban.status';

    protected function records(): Collection
    {
        return $this->getEloquentQuery()
            ->where('broker_id', auth()->id())
            ->when(method_exists(static::$model, 'scopeOrdered'), fn ($query) => $query->ordered())
            ->get();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->model(Lead::class)
                ->form([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(100),
                    TextInput::make('email')
                        ->required(),
                    TextInput::make('phone')
                        ->required()
                        ->maxLength(20)
                        ->mask(RawJs::make(<<<'JS'
                            $input.length <= 14 ? '(99) 9999-9999' : '(99) 99999-9999'
                        JS)),
                    Textarea::make('message')
                ])
                ->mutateFormDataUsing(fn (array $data): array => [
                    ...$data,
                    'status' => LeadStatus::Assigned,
                    'subject' => 'buy',
                ])
                ->createAnother(false)
                ->after(function (Lead $record) {
                    FollowUp::factory()->create([
                        'status' => FollowUpStatus::Lead,
                        'broker_id' => auth()->id(),
                        'lead_id' => $record->getKey(),
                    ]);
                })
        ];
    }
}
