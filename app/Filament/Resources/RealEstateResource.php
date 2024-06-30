<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RealEstateResource\Pages;
use App\Filament\Resources\RealEstateResource\RelationManagers;
use App\Filament\Resources\RealEstateResource\RelationManagers\OwnersRelationManager;
use App\Models\RealEstate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RealEstateResource extends Resource
{
    protected static ?string $model = RealEstate::class;

    protected static ?string $modelLabel = 'imóvel';

    protected static ?string $pluralModelLabel = 'imóveis';

    protected static ?string $slug = 'imoveis';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),

                TextColumn::make('zip_code')
                    ->label('CEP'),

                TextColumn::make('created_at')
                    ->label('Cadastrado em')
                    ->formatStateUsing(fn ($state) => $state->format('d/m/Y'))
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Última atualização')
                    ->since()
                    ->sortable(),
            ])
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
            'create' => Pages\CreateRealEstate::route('/create'),
            'edit' => Pages\EditRealEstate::route('/{record}/edit'),
        ];
    }
}
