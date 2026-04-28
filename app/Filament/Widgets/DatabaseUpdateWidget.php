<?php

namespace App\Filament\Widgets;

use Exception;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DatabaseUpdateWidget extends Widget
{
    protected string $view = 'filament.widgets.database-update-widget';

    public function updateDatabase()
    {
        try {
            // products tábla törlése
            DB::table('products')->truncate();

            // Csak a ProductSeeder osztály seedelése
            Artisan::call('db:seed', [
                '--class' => 'ProductSeeder',
            ]);

            Notification::make()
                ->title(__('Adatbázis sikeresen frissítve!'))
                ->success()
                ->send();

        } catch (Exception $e) {
            Notification::make()
                ->title(__('Hiba történt az adatbázis frissítése során!'))
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
