<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('gender')
                    ->badge(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),

                TextColumn::make('status')
                    ->sortable()
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Active',
                        2 => 'Blocked',
                        0 => 'Inactive',
                        default => 'Unknown',

                    })
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        1 => 'success',   // Green
                        2 => 'danger',    // Red
                        0 => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('company.name')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
