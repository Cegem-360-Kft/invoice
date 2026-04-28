<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\WoocommerceProductResource\Pages\ListWoocommerceProducts;
use App\Filament\Resources\WoocommerceProductResource\Pages\CreateWoocommerceProduct;
use App\Filament\Resources\WoocommerceProductResource\Pages\EditWoocommerceProduct;
use App\Filament\Resources\WoocommerceProductResource\Pages;
use App\Filament\Resources\WoocommerceProductResource\RelationManagers;
use App\Models\WoocommerceProduct;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WoocommerceProductResource extends Resource
{
    protected static ?string $model = WoocommerceProduct::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('wordpress_id'),
                TextInput::make('name'),
                TextInput::make('sku')
                    ->label('SKU'),
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
            'index' => ListWoocommerceProducts::route('/'),
            'create' => CreateWoocommerceProduct::route('/create'),
            'edit' => EditWoocommerceProduct::route('/{record}/edit'),
        ];
    }
}
