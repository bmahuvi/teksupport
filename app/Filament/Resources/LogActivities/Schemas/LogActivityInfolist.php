<?php

namespace App\Filament\Resources\LogActivities\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LogActivityInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Activity Details')
                    ->schema([
                        Grid::make(3)->schema([
                            TextEntry::make('description')->label('Action'),
                            TextEntry::make('log_name'),
                            TextEntry::make('causer.name')->label('Performed By')->placeholder('System'),
                            TextEntry::make('subject_type')
                                ->formatStateUsing(fn($state) => class_basename($state))
                                ->label('Model'),
                            TextEntry::make('updated_at')->dateTime(),
                            TextEntry::make('created_at')->dateTime(),
                        ]),
                    ]),

                Section::make('Changes')
                    ->schema([
                        TextEntry::make('properties.attributes')
                            ->label('New Values')
                            ->formatStateUsing(fn($state) => json_encode($state, JSON_PRETTY_PRINT)
                            )
                            ->copyable()
                            ->columnSpanFull(),

                        TextEntry::make('properties.old')
                            ->label('Old Values')
                            ->formatStateUsing(fn($state) => json_encode($state, JSON_PRETTY_PRINT)
                            )
                            ->copyable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
