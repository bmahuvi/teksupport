<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordClasses(fn(Model $record) => match (true) {
                method_exists($record, 'isNotOpened') && $record->isNotOpened()
                => 'font-bold bg-primary-50/50 dark:bg-primary-900/20',
                default => null,
            })
            ->columns([
                TextColumn::make('unread_indicator')
                    ->label('')
                    ->badge()
                    ->color('info')
                    ->size('sm')
                    ->state(fn(Model $record) => method_exists($record, 'isNotOpened') && $record->isNotOpened()
                        ? 'New'
                        : null
                    )
                    ->extraAttributes(['class' => 'text-[11px]'])
                    ->tooltip(fn($state) => $state ? 'Tooltip' : null),

                TextColumn::make('title')
                    ->weight(fn(Model $record) => (method_exists($record, 'isNotOpened') && $record->isNotOpened()) ? 'bold' : 'medium')
                    ->limit(40)
                    ->tooltip(fn(Ticket $record): string => $record->title)
                    ->searchable(),

                TextColumn::make('createdBy.name')
                    ->sortable(),

                TextColumn::make('ticket_number')
                    ->searchable(),

                TextColumn::make('priority')
                    ->badge(),

                TextColumn::make('company.name')
                    ->searchable(),

                TextColumn::make('ticketStatus.name')
                    ->label('Status')
                    ->formatStateUsing(fn($record) => $record->ticketStatus?->name ?
                        "<span style='
                                display: inline-flex;
                                align-items: center;
                                background-color: {$record->ticketStatus->color}10;
                                color: {$record->ticketStatus->color};
                                padding: 0.3rem 0.8rem;
                                border-radius: 9999px;
                                font-size: 0.7rem;
                                font-weight: 600;
                                line-height: 1;
                                border: 1.5px solid {$record->ticketStatus->color};
                                white-space: nowrap;
                            '>{$record->ticketStatus->name}</span>"
                        : ''
                    )
                    ->html(),

                IconColumn::make('to_main')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('company')
                    ->relationship('company', 'name')
                    ->preload(),

                SelectFilter::make('ticketStatus')
                    ->label('Status')
                    ->relationship('ticketStatus', 'name')
                    ->preload(),

                SelectFilter::make('priority')
                    ->options(TicketPriority::class)
                    ->preload()
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
