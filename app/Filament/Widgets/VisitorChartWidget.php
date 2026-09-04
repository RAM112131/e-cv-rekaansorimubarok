<?php

namespace App\Filament\Widgets;

use App\Models\Visitor;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class VisitorChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Pengunjung (7 Hari Terakhir)';

    protected static ?int $sort = 2;

    protected ?string $pollingInterval = null;

    // Lazy dinonaktifkan karena di php artisan serve (single-thread), 
    // request concurrent AJAX malah bikin antrian panjang dan loading lama.
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        return Cache::remember('visitor.chart.data', 300, function () {
            $startDate = Carbon::now()->subDays(6)->startOfDay();

            $rawCounts = Visitor::where('visited_at', '>=', $startDate)
                ->selectRaw('DATE(visited_at) as date_key, COUNT(*) as aggregate')
                ->groupBy('date_key')
                ->pluck('aggregate', 'date_key');

            $days = collect(range(6, 0))->map(function ($i) {
                return Carbon::now()->subDays($i)->format('Y-m-d');
            });

            $counts = $days->map(fn ($date) => (int) ($rawCounts[$date] ?? 0));

            $labels = $days->map(function ($date) {
                return Carbon::parse($date)->format('d M');
            });

            return [
                'datasets' => [
                    [
                        'label' => 'Pengunjung Unik',
                        'data' => $counts->toArray(),
                        'fill' => 'start',
                        'borderColor' => '#f59e0b',
                        'backgroundColor' => 'rgba(245, 158, 11, 0.12)',
                        'borderWidth' => 2,
                        'pointBackgroundColor' => '#f59e0b',
                        'pointRadius' => 3,
                        'tension' => 0.4,
                    ],
                ],
                'labels' => $labels->toArray(),
            ];
        });
    }

    protected function getType(): string
    {
        return 'line';
    }
}