<?php

namespace App\Filament\Resources\SystemSettings\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SystemSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(3)
                    ->schema([
                        TextInput::make('key')
                            ->required(),
                        TextInput::make('value')
                            ->required(),
                        TextInput::make('type')
                            ->required(),
                        Textarea::make('description')
                            ->columnSpanFull(),
                    ])
            ]);
    }
}
