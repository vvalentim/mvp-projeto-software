<?php

namespace App\Filament\Resources;

use App\Enums\FollowUpStatus;
use App\Filament\Resources\FollowUpResource\Pages;
use App\Models\Customer;
use App\Models\FollowUp;
use App\Models\Lead;
use App\Models\RealEstate;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class FollowUpResource extends Resource
{
    protected static ?string $model = FollowUp::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $modelLabel = 'negociação';

    protected static ?string $pluralModelLabel = 'negociações';

    protected static ?string $slug = 'negociacoes';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereBelongsTo(auth()->user())
            ->with(['user', 'estate', 'customer.person']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Filament does not render a column when it is undefined or null
                // We can use the 'id' column to force render it
                // This is a hacky way to display a 'title' depending on if the lead or customer data is defined
                TextColumn::make('id')
                    ->label('Cliente')
                    ->formatStateUsing(fn (FollowUp $record): string => $record->getKanbanRecordTitle()),

                TextColumn::make('created_at')
                    ->label('Data de início')
                    ->since()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (FollowUpStatus $state): string => $state->getLocalizedLabel())
                    ->color(fn (FollowUpStatus $state): string => match ($state) {
                        FollowUpStatus::Lead => 'gray',
                        FollowUpStatus::Prospect => 'warning',
                        FollowUpStatus::Opportunity => 'info',
                        FollowUpStatus::Concluded => 'success'
                    })
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFollowUps::route('/'),
            // 'create' => Pages\CreateFollowUp::route('/criar'),
            'edit' => Pages\EditFollowUp::route('/{record}/editar'),
        ];
    }
}
