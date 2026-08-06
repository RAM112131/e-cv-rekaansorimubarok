<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MessageChartWidget;
use App\Filament\Widgets\RecentMessagesWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use App\Filament\Widgets\VisitorChartWidget;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $title = 'Dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            VisitorChartWidget::class,
            MessageChartWidget::class,
            RecentMessagesWidget::class,
        ];
    }

    public function getColumns(): int|array
    {
        return 2;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view_site')
                ->label('Lihat Website Portofolio')
                ->icon('heroicon-o-globe-alt')
                ->color('gray')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }
}
