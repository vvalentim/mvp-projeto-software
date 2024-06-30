<?php

namespace App\Filament\Resources;

use App\Enums\RealEstateTypes;
use App\Filament\Resources\RealEstateResource\Pages;
use App\Models\Customer;
use App\Models\RealEstate;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Config;

class RealEstateResource extends Resource
{
    protected static ?string $model = RealEstate::class;

    protected static ?string $modelLabel = 'imóvel';

    protected static ?string $pluralModelLabel = 'imóveis';

    protected static ?string $slug = 'imoveis';

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    protected static ?string $createActionLabel = 'Cadastrar imóvel';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título'),

                TextColumn::make('type')
                    ->label('Tipo do imóvel')
                    ->formatStateUsing(fn ($state) => $state->getLabel()),

                TextColumn::make('price')
                    ->label('Preço')
                    ->formatStateUsing(fn ($state) => "R$ {$state}"),

                TextColumn::make('zip_code')
                    ->label('CEP'),

                TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->since()
                    ->sortable(),
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
            'index' => Pages\ListRealEstates::route('/'),
            'create' => Pages\CreateRealEstate::route('/criar'),
            'edit' => Pages\EditRealEstate::route('/{record}/editar'),
        ];
    }

    public static function getDetailsFormSchema(): array
    {
        return [
            Section::make('Detalhes do imóvel')
                ->schema([
                    Group::make()
                        ->schema([
                            TextInput::make('title')
                                ->label('Título do anúncio')
                                ->columnSpan(3)
                                ->required()
                                ->validationMessages([
                                    'required' => 'O campo título do anúncio é obrigatório.'
                                ]),

                            Select::make('type')
                                ->label('Tipo do imóvel')
                                ->native(false)
                                ->options(RealEstateTypes::localizedFilterOptions())
                                ->columnSpan(3)
                                ->reactive()
                                ->required()
                                ->validationMessages([
                                    'required' => 'O campo tipo do imóvel é obrigatório.'
                                ]),

                            Textarea::make('description')
                                ->label('Descrição')
                                ->columnSpanFull(),
                        ])
                        ->columns(6),

                    Group::make()
                        ->schema([
                            TextInput::make('num_rooms')
                                ->label('Quantidade quartos')
                                ->mask('99')
                                ->numeric()
                                ->minValue(1)
                                ->maxValue(99)
                                ->required()
                                ->columnSpan(2),

                            TextInput::make('num_suite')
                                ->label('Quantidade suítes')
                                ->mask('99')
                                ->minValue(0)
                                ->maxValue(99)
                                ->required()
                                ->columnSpan(2),

                            TextInput::make('num_garage')
                                ->label('Vagas garagem')
                                ->mask('99')
                                ->minValue(0)
                                ->maxValue(99)
                                ->required()
                                ->columnSpan(2),
                        ])
                        ->hidden(fn (Get $get) => $get('type') === RealEstateTypes::Landplot->value)
                        ->columns(6),

                    Group::make()
                        ->schema([
                            TextInput::make('price')
                                ->label('Preço')
                                ->prefix('R$')
                                ->mask(RawJs::make('$money($input, \',\')'))
                                ->stripCharacters('.')
                                ->columnSpan(2)
                                ->required(),

                            TextInput::make('tax_iptu')
                                ->label('Valor IPTU')
                                ->prefix('R$')
                                ->mask(RawJs::make('$money($input, \',\')'))
                                ->stripCharacters('.')
                                ->columnSpan(2)
                                ->required(),

                            TextInput::make('tax_condominium')
                                ->label('Valor condomínio')
                                ->prefix('R$')
                                ->mask(RawJs::make('$money($input, \',\')'))
                                ->stripCharacters('.')
                                ->hidden(fn (Get $get) => !in_array($get('type'), [RealEstateTypes::Apartment->value, RealEstateTypes::Condominium->value]))
                                ->columnSpan(2),
                        ])
                        ->columns(6),


                    Group::make()
                        ->schema([
                            TextInput::make('area_total')
                                ->label('Área total')
                                ->suffix('m²')
                                ->mask(RawJs::make('$money($input, \',\')'))
                                ->stripCharacters('.')
                                ->columnSpan(2)
                                ->required(),

                            TextInput::make('area_built')
                                ->label('Área construída')
                                ->suffix('m²')
                                ->mask(RawJs::make('$money($input, \',\')'))
                                ->stripCharacters('.')
                                ->columnSpan(2)
                                ->required(),
                        ])
                        ->columns(6),
                ]),

            Section::make('Endereço do imóvel')
                ->schema([
                    TextInput::make('zip_code')
                        ->label('CEP')
                        ->mask('99999-999')
                        ->length(9)
                        ->columnSpan(1)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo CEP é obrigatório.',
                            'size' => 'CEP inválido.',
                        ]),

                    Select::make('address_state')
                        ->label('Estado')
                        ->native(false)
                        ->options(Config::get('constants.OPTIONS_UF'))
                        ->columnSpan(2)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo UF é obrigatório.'
                        ]),

                    TextInput::make('address_city')
                        ->label('Cidade')
                        ->columnSpan(3)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo cidade é obrigatório.'
                        ]),

                    TextInput::make('address_neighborhood')
                        ->label('Bairro')
                        ->columnSpan(3)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo bairro é obrigatório.'
                        ]),

                    TextInput::make('address_street')
                        ->label('Logradouro')
                        ->columnSpan(3)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo logradouro é obrigatório.'
                        ]),

                    TextInput::make('address_number')
                        ->label('Número')
                        ->columnSpan(3)
                        ->required()
                        ->validationMessages([
                            'required' => 'O campo número é obrigatório.'
                        ]),

                    TextInput::make('address_complement')
                        ->label('Complemento')
                        ->columnSpan(3),
                ])
                ->columns(6)
        ];
    }

    public static function getOwnersRepeater(): Repeater
    {
        return Repeater::make('estateOwners')
            ->relationship()
            ->dehydrated()
            ->schema([
                Select::make('customer_id')
                    ->label('Proprietário')
                    ->reactive()
                    ->searchable()
                    ->distinct()
                    ->required()
                    ->validationMessages([
                        'required' => 'Busque e selecione o proprietário que será vinculado ao imóvel.'
                    ])
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
            ])
            ->extraItemActions([
                Action::make('cadastro')
                    ->tooltip('Visualizar cadastro do cliente')
                    ->icon('heroicon-m-arrow-top-right-on-square')
                    ->url(function (array $arguments, Repeater $component): ?string {
                        $itemData = $component->getRawItemState($arguments['item']);

                        $customer = Customer::find($itemData['customer_id']);

                        if (!$customer) {
                            return null;
                        }

                        return "clientes/{$itemData['customer_id']}/editar";
                    }, shouldOpenInNewTab: true)
                    ->hidden(fn (array $arguments, Repeater $component): bool => blank($component->getRawItemState($arguments['item'])['customer_id'])),
            ])
            ->minItems(1)
            ->maxItems(10)
            ->validationMessages(['min' => 'O imóvel deve conter ao menos um proprietário vinculado.'])
            ->addActionLabel('Adicionar proprietário')
            ->hiddenLabel();
    }
}
