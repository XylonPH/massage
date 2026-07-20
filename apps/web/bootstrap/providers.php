<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\EditorialPanelProvider;
use App\Providers\Filament\ModerationPanelProvider;
use App\Providers\Filament\SystemPanelProvider;

return [
    AppServiceProvider::class,
    EditorialPanelProvider::class,
    ModerationPanelProvider::class,
    SystemPanelProvider::class,
];
