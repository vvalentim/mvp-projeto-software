<?php

namespace App\Filament\Resources;

use App\Enums\LeadStatus;
use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Models\RealEstate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static ?string $navigationGroup = 'CRM';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(auth()->user());
    }

    public static function form(Form $form): Form
    {
        return $form
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

                Select::make('real_estate_id')
                    ->label('Imóvel de interesse')
                    ->placeholder('Selecione um imóvel')
                    ->searchPrompt('Digite o título ou CEP do imóvel para buscá-lo')
                    ->relationship('estate')
                    ->searchable()
                    ->getOptionLabelFromRecordUsing(fn (RealEstate $estate) => $estate->getSearchLabel())
                    ->preload()
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),

                TextColumn::make('phone')
                    ->label('Telefone'),

                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Responsável'),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (LeadStatus $state): string => $state->getLocalizedLabel())
                    ->color(fn (LeadStatus $state): string => match ($state) {
                        LeadStatus::Unverified => 'danger',
                        LeadStatus::Verified => 'info',
                        LeadStatus::Assigned => 'success'
                    })
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(LeadStatus::localizedFilterOptions())
            ])
            ->actions([
                Tables\Actions\EditAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/criar'),
            'edit' => Pages\EditLead::route('/{record}/editar'),
        ];
    }
}
