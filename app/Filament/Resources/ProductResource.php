<?php

namespace App\Filament\Resources;

use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Resources\ProductResource\Pages\ListProducts;
use App\Filament\Resources\ProductResource\Pages\CreateProduct;
use App\Filament\Resources\ProductResource\Pages\EditProduct;
use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nev')->label(__('Név'))
                    ->required(),
                TextInput::make('sku')
                    ->label('SKU')
                    ->required(false),
                TextInput::make('ean')
                    ->label('EAN')
                    ->required(false),
                TextInput::make('price')
                    ->label('Netto ár')
                    ->required(false)
                    ->numeric()
                    ->default(0),
                TextInput::make('price_kivitelezok')
                    ->label('Kivitelezői ár')
                    ->required(false)
                    ->numeric()
                    ->default(0),
                TextInput::make('price_kp_elore_harminc')
                    ->label('Készpénz előre 30 %')
                    ->required(false)
                    ->numeric()
                    ->default(0),
                TextInput::make('price_kp_elore_huszonot')
                    ->label('Készpénz előre 25 %')
                    ->required(false),
                TextInput::make('storage')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nev')
                    ->searchable(),
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable(),
                TextColumn::make('ean')
                    ->searchable(),
                TextColumn::make('price')
                    ->searchable(),
                TextColumn::make('price_kivitelezok')
                    ->searchable(),
                TextColumn::make('price_kp_elore_harminc')
                    ->searchable(),
                TextColumn::make('price_kp_elore_huszonot')
                    ->searchable(),
                TextColumn::make('storage')
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
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
