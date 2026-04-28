<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0;

    protected ?string $pollingInterval = '60s';

    protected function getStats(): array
    {
        return Cache::remember('stats-overview-widget', now()->addMinutes(5), function () {
            $totalProducts = Product::count();

            $newProducts = $this->getRecent('created_at');
            $updatedProducts = $this->getRecent('updated_at');

            return [
                Stat::make(__('Összes Termék'), $totalProducts)
                    ->description(__('Összes termék darabszáma'))
                    ->chart([1, 1, 1, 1, 1, 1, 1, 1]),
                Stat::make(__('Új termékek az elmult 24 órában'), $newProducts->count())
                    ->description(__('Az elmult 24 órában hozzáadott termékek darabszáma'))
                    ->chart($this->getChartData($newProducts, 'created_at'))
                    ->color('info'),
                Stat::make(__('Frissített termékek az elmult 24 órában'), $updatedProducts->count())
                    ->description(__('Az elmult 24 órában frissített termékek darabszáma'))
                    ->chart($this->getChartData($updatedProducts, 'updated_at'))
                    ->color('info'),
            ];
        });
    }

    private function getRecent(string $column): Collection
    {
        return Product::query()
            ->select($column)
            ->where($column, '>=', now()->subDay())
            ->get();
    }

    private function getChartData(Collection $products, string $column): array
    {
        $grouped = $products->groupBy(function ($product) use ($column) {
            $hour = $product->{$column}->hour;
            $bin = intdiv($hour, 4) * 4;

            return $product->{$column}->format('Y-m-d').' '.str_pad((string) $bin, 2, '0', STR_PAD_LEFT).':00:00';
        })->map->count()->toArray();

        $chartData = array_fill(0, 6, 0);
        foreach ($grouped as $key => $count) {
            $hour = (int) substr($key, 11, 2);
            $chartData[intdiv($hour, 4)] = $count;
        }

        $currentIndex = intdiv(now()->hour, 4);

        return array_merge(
            array_slice($chartData, $currentIndex + 1),
            array_slice($chartData, 0, $currentIndex + 1),
        );
    }
}
