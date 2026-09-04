<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class MessageChartWidget extends ChartWidget
{
    protected ?string $heading = 'Grafik Pesan Masuk (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    protected ?string $pollingInterval = null;

    // Lazy dinonaktifkan karena di php artisan serve (single-thread), 
    // request concurrent AJAX malah bikin antrian panjang dan loading lama.
    protected static bool $isLazy = false;

    protected function getData(): array
    {
        return Cache::remember('message.chart.data', 300, function () {
            $startDate = Carbon::now()->subDays(6)->startOfDay();

            $rawCounts = Message::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date_key, COUNT(*) as aggregate')
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
                        'label' => 'Pesan Diterima',
                        'data' => $counts->toArray(),
                        'fill' => 'start',
                        'borderColor' => '#10b981',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.25)',
                        'borderWidth' => 2,
                        'borderRadius' => 6,
                    ],
                ],
                'labels' => $labels->toArray(),
            ];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }
}