<?php

namespace App\Filament\Widgets;

use Exception;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\DB;

class DatabaseProductsClearWidget extends Widget
{
    protected string $view = 'filament.widgets.database-products-clear-widget';

    public function clearDatabase()
    {
        try {
            // products tábla törlése
            DB::table('products')->truncate();

            Notification::make()
                ->title(__('Termékek sikeresen kiürítve!'))
                ->success()
                ->send();
        } catch (Exception $e) {
            Notification::make()
                ->title(__('Hiba történt az termékek kiürítése során!'))
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
