<?php

namespace App\Filament\Resources\WoocommerceProductResource\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Resources\WoocommerceProductResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWoocommerceProduct extends EditRecord
{
    protected static string $resource = WoocommerceProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
