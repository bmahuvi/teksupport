<?php

namespace App\Filament\Resources\LogActivities\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;

class LogActivitiesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('log_name')
                    ->label('Log')
                    ->badge(),

                TextColumn::make('description')
                    ->label('Action')
                    ->searchable(),

                TextColumn::make('causer.name')
                    ->label('User')
                    ->placeholder('System'),

                TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn($state) => class_basename($state)
                    ),

                TextColumn::make('subject_id')
                    ->label('Record ID'),

                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('subject_type')
                    ->options(
                        Activity::query()
                            ->select('subject_type')
                            ->distinct()
                            ->pluck('subject_type', 'subject_type')
                            ->mapWithKeys(fn($value) => [
                                $value => class_basename($value),
                            ])
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([])
            ->defaultSort('created_at', 'desc');
    }
}
