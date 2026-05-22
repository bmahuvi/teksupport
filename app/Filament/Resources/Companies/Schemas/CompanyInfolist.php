<?php

namespace App\Filament\Resources\Companies\Schemas;

use App\Models\Company;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompanyInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Company Details')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('name'),

                        TextEntry::make('phone')
                            ->placeholder('-'),

                        TextEntry::make('email')
                            ->label('Email')
                            ->placeholder('-'),

                        IconEntry::make('is_active')
                            ->label('Active')
                            ->boolean(),
                        IconEntry::make('is_main')
                            ->label('Main')
                            ->boolean(),
                        TextEntry::make('region.name')
                            ->label('Region')
                            ->placeholder('-'),
                        TextEntry::make('district.name')
                            ->label('District')
                            ->placeholder('-'),
                        TextEntry::make('creator.name')
                            ->numeric()
                            ->placeholder('-'),
                        TextEntry::make('deleted_at')
                            ->label('Deleted At')
                            ->dateTime()
                            ->visible(fn(Company $record): bool => $record->trashed()),
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label('Updated At')
                            ->since()
                            ->placeholder('-'),
                    ])
            ]);
    }
}
