<?php

namespace App\Filament\Widgets;

use Exception;
use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class DatabaseUpdateWidget extends Widget
{
    protected string $view = 'filament.widgets.database-update-widget';

    public function updateDatabase(): void
    {
        try {
            $exitCode = Artisan::call('products:sync', ['--prune' => true]);

            Cache::forget('stats-overview-widget');

            if ($exitCode === SymfonyCommand::SUCCESS) {
                Notification::make()
                    ->title(__('Adatbázis sikeresen frissítve!'))
                    ->success()
                    ->send();

                return;
            }

            Notification::make()
                ->title(__('A frissítés hibára futott!'))
                ->danger()
                ->body(trim(Artisan::output()))
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
