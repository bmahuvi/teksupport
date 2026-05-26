<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Select;

class RegionComponent
{
    public static function make(): Select
    {
        return Select::make('region_id')
            ->relationship(
                'region',
                'name')
            ->live()
            ->searchPrompt('Search regions by name')
            ->required();

    }
}
