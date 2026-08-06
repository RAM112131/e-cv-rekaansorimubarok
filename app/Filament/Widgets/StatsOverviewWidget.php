<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class StatsOverviewWidget extends Widget
{
    protected string $view = 'filament.widgets.stats-overview-widget';

    protected static ?int $sort = 0;
    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';
}
