<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Filament\Components\DistrictComponent;
use App\Filament\Components\RegionComponent;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('phone')
                            ->maxLength(10)
                            ->minLength(10)
                            ->placeholder('07xxxxxxxx')
                            ->tel()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email')
                            ->email(),

                        RegionComponent::make(),

                        DistrictComponent::make(),
                    ])
            ]);
    }
}
