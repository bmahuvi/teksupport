<?php

namespace App\Filament\Resources\LogActivities\Pages;

use App\Filament\Resources\LogActivities\LogActivityResource;
use Filament\Resources\Pages\ListRecords;

class ListLogActivities extends ListRecords
{
    protected static string $resource = LogActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
