<?php

namespace App\Filament\Resources\Users\Schemas;


use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('User Details')
                    ->description('Details about the user')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Name'),

                                TextEntry::make('email')
                                    ->label('Email address'),

                                TextEntry::make('gender')
                                    ->badge(),

                                TextEntry::make('company.name')
                                    ->label('Company'),


                            ]),
                    ])
                    ->columnSpanFull(),

                Section::make('Security')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('status')
                                    ->badge()
                                    ->formatStateUsing(function ($state) {
                                        return match ($state) {
                                            1 => 'Active',
                                            2 => 'Blocked',
                                            0 => 'Inactive',
                                            default => 'Unknown',
                                        };
                                    })
                                    ->color(function ($state) {
                                        return match ($state) {
                                            1 => 'success',
                                            2 => 'danger',
                                            0 => 'warning',
                                            default => 'gray',
                                        };
                                    }),

                                TextEntry::make('roles')
                                    ->label('Roles')
                                    ->badge()
                                    ->formatStateUsing(fn($state, $record) => $record->roles->pluck('name')->join(', '))
                                    ->placeholder('-'),

                                TextEntry::make('latestLogin.created_at')
                                    ->label('Latest Login')
                                    ->placeholder('Never logged in')
                                    ->since()
                            ])
                    ])
                    ->columnSpanFull(),


            ]);
    }


}
