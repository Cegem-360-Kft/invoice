<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\WoocommerceProductVariationResource\Pages\ListWoocommerceProductVariations;
use App\Filament\Resources\WoocommerceProductVariationResource\Pages\CreateWoocommerceProductVariation;
use App\Filament\Resources\WoocommerceProductVariationResource\Pages\EditWoocommerceProductVariation;
use App\Filament\Resources\WoocommerceProductVariationResource\Pages;
use App\Filament\Resources\WoocommerceProductVariationResource\RelationManagers;
use App\Models\WoocommerceProductVariation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WoocommerceProductVariationResource extends Resource
{
    protected static ?string $model = WoocommerceProductVariation::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('wordpress_id'),
                TextInput::make('name'),
                TextInput::make('sku')
                    ->label('SKU'),
                TextInput::make('woocommerce_product_id')
                    ->required()
                    ->numeric(),
                Select::make('product_id')
                    ->relationship('product', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('wordpress_id')
                    ->searchable(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('woocommerce_product_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('product.id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => ListWoocommerceProductVariations::route('/'),
            'create' => CreateWoocommerceProductVariation::route('/create'),
            'edit' => EditWoocommerceProductVariation::route('/{record}/edit'),
        ];
    }
}
