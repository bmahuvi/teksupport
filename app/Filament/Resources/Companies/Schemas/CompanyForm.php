<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Models\District;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('phone')
                    ->maxLength(10)
                    ->minLength(10)
                    ->placeholder('07xxxxxxxx')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_main')
                    ->required(),
                Select::make('region_id')
                    ->relationship('region', 'name')
                    ->live()
                    ->required(),
                Select::make('district_id')
                    ->label('District')
                    ->options(function (callable $get) {
                        $regionId = $get('region_id');

                        if (!$regionId) {
                            return [];
                        }
                        return District::where('region_id', $regionId)
                            ->pluck('name', 'id');
                    })
                    ->required()
                    ->reactive()
                    ->disabled(fn(callable $get) => !$get('region_id')),
            ]);
    }
}
