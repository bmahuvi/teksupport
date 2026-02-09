<?php

namespace App\Filament\Resources\LogActivities\Pages;

use App\Filament\Resources\LogActivities\LogActivityResource;
use Filament\Resources\Pages\ViewRecord;

class ViewLogActivity extends ViewRecord
{
    protected static string $resource = LogActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
