<?php

namespace App\Filament\Resources\WoocommerceProductResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\WoocommerceProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWoocommerceProducts extends ListRecords
{
    protected static string $resource = WoocommerceProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
