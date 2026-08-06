<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeBannerWidget extends Widget
{
    protected string $view = 'filament.widgets.welcome-banner-widget';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';
}
