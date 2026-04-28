<?php

namespace App\Filament\Resources\WoocommerceProductVariationResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\WoocommerceProductVariationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWoocommerceProductVariations extends ListRecords
{
    protected static string $resource = WoocommerceProductVariationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
