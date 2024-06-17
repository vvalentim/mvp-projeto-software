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
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
        return parent::getEloquentQuery()->whereBelongsTo(auth()->user());
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

                TextColumn::make('estate.title')
                    ->label('Imóvel'),

                TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->since()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (FollowUpStatus $state): string => $state->getLabel())
            ])
            ->defaultSort('updated_at', 'desc')
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
            'create' => Pages\CreateFollowUp::route('/criar'),
            'edit' => Pages\EditFollowUp::route('/{record}/editar'),
        ];
    }

    public static function fillCustomFieldsWithRecord(Component $group, array $record): void
    {
        $fields = $group->getChildComponents();

        foreach ($fields as $field) {
            $path = $field->getStatePath(false);

            if (array_key_exists($path, $record)) {
                $field->state($record[$path]);
            }
        }
    }

    public static function handleLeadSelectState(Get $get, Select $component): void
    {
        $record = Lead::where('id', $get('lead_id'))->first();

        if ($record) {
            $group = $component->getContainer()->getComponent('dynamicGroupLead');

            static::fillCustomFieldsWithRecord($group, Arr::dot($record->toArray()));
        }
    }

    public static function handleCustomerSelectState(Get $get, Select $component): void
    {
        if ($get('customer_id')) {

            $record = Customer::where('id', $get('customer_id'))->with('person')->first();

            if ($record) {
                $group = $component->getContainer()->getComponent('dynamicGroupCustomer');

                static::fillCustomFieldsWithRecord($group, Arr::dot($record->toArray()));
            }
        }
    }

    public static function handleEstateSelectState(Get $get, Select $component): void
    {
        if ($get('real_estate_id')) {
            $record = RealEstate::where('id', $get('real_estate_id'))->first();

            if ($record) {
                $group = $component->getContainer()->getComponent('dynamicGroupEstate');

                static::fillCustomFieldsWithRecord($group, Arr::dot($record->toArray()));
            }
        }
    }

    public static function getLeadSelector(): Select
    {
        return Select::make('lead_id')
            ->label('Lead')
            ->placeholder('Digite o nome do lead ou o email para buscar...')
            ->searchable()
            ->required()
            ->reactive()
            ->getSearchResultsUsing(function (string $search) {
                return Lead::query()
                    ->where('user_id', auth()->id())
                    ->where(function (Builder $query) use ($search) {
                        $query->where('name', 'ilike', "{$search}%")
                            ->orWhere('email', 'ilike', "{$search}%");
                    })
                    ->limit(30)
                    ->get()
                    ->mapWithKeys(fn (Lead $record) => [$record->id => $record->getSearchLabel()]);
            })
            ->afterStateUpdated(fn (Get $get, Select $component) => static::handleLeadSelectState($get, $component));
    }

    public static function getCustomerSelector(): Select
    {
        return Select::make('customer_id')
            ->label('Cliente')
            ->placeholder('Digite o nome do cliente ou o documento para buscar...')
            ->searchable()
            ->required()
            ->reactive()
            ->getSearchResultsUsing(function (string $search) {
                return Customer::query()
                    ->with('person')
                    ->select(['customers.id', 'customers.person_id'])
                    ->join('people', 'people.id', '=', 'customers.person_id')
                    ->where('people.name', 'ilike', "{$search}%")
                    ->orWhere('people.num_registry', 'like', "{$search}%")
                    ->limit(30)
                    ->get()
                    ->mapWithKeys(fn (?Customer $record) => [$record->id => $record?->person->getSearchLabel()]);
            })
            ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->person->getSearchLabel())
            ->afterStateUpdated(fn (Get $get, Select $component) => static::handleCustomerSelectState($get, $component))
            ->afterStateHydrated(fn (Get $get, Select $component) => static::handleCustomerSelectState($get, $component));
    }

    public static function getEstateSelector(): Select
    {
        return Select::make('real_estate_id')
            ->label('Imóvel')
            ->placeholder('Digite o título do anúncio ou o CEP do imóvel para buscar...')
            ->searchable()
            ->required()
            ->reactive()
            ->getSearchResultsUsing(function (string $search) {
                return RealEstate::query()
                    ->where('title', 'ilike', "{$search}%")
                    ->orWhere('zip_code', 'like', "{$search}%")
                    ->limit(30)
                    ->get()
                    ->mapWithKeys(fn (?RealEstate $record) => [$record->id => $record?->getSearchLabel()]);
            })
            ->getOptionLabelUsing(fn ($value): ?string => RealEstate::find($value)?->getSearchLabel())
            ->afterStateUpdated(fn (Get $get, Select $component) => static::handleEstateSelectState($get, $component))
            ->afterStateHydrated(fn (Get $get, Select $component) => static::handleEstateSelectState($get, $component));
    }

    public static function getLeadGroup(): Group
    {
        return Group::make()
            ->schema([
                TextInput::make('name')
                    ->label('Nome')
                    ->disabled(),

                TextInput::make('email')
                    ->label('Endereço de e-mail')
                    ->disabled(),

                TextInput::make('phone')
                    ->label('Telefone')
                    ->disabled()
            ])
            ->key('dynamicGroupLead')
            ->visible(fn (callable $get) => $get('lead_id'));
    }

    public static function getCustomerGroup(): Group
    {
        return Group::make()
            ->schema([
                TextInput::make('person.name')
                    ->label('Nome')
                    ->disabled(),

                TextInput::make('person.num_registry')
                    ->label('CPF/CNPJ')
                    ->disabled(),

                TextInput::make('person.num_identity')
                    ->label('RG')
                    ->disabled()
                    ->hidden(fn ($state): bool => empty($state)),

                TextInput::make('person.phone_1')
                    ->label('Telefone')
                    ->disabled()
            ])
            ->key('dynamicGroupCustomer')
            ->visible(fn (callable $get) => $get('customer_id'));
    }

    public static function getEstateGroup(): Group
    {
        return Group::make()
            ->schema([
                TextInput::make('title')
                    ->label('Título do anúncio')
                    ->disabled(),
                TextInput::make('type')
                    ->label('Tipo do imóvel')
                    ->disabled(),
                TextInput::make('description')
                    ->label('Descrição do imóvel')
                    ->disabled(),
                TextInput::make('zip_code')
                    ->label('CEP')
                    ->disabled(),
                TextInput::make('price')
                    ->label('Preço')
                    ->prefix('R$')
                    ->disabled()
            ])
            ->key('dynamicGroupEstate')
            ->visible(fn (callable $get) => $get('real_estate_id'));
    }
}
