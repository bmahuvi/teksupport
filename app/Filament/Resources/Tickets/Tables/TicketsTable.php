<?php

namespace App\Filament\Resources\Tickets\Tables;

use App\Enums\TicketPriority;
use App\Models\Ticket;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TicketsTable
{
    public static function configure(Table $table): Table
    {
        $user = Filament::auth()->user();

        return $table
            ->modifyQueryUsing(function (Builder $query) use ($user) {
                if (!$user) {
                    $query->whereRaw('1 = 0');
                    return;
                }

                $query->where('created_by', $user->getKey());
            })
            ->recordClasses(fn(Model $record) => match (true) {
                $record->isNotOpened()
                => 'font-bold bg-primary-50/50 dark:bg-primary-900/20',
                default => null,
            })
            ->columns([
                TextColumn::make('unread_indicator')
                    ->label('')
                    ->badge()
                    ->color('info')
                    ->size('sm')
                    ->state(fn(Model $record) => $record->isNotOpened()
                        ? 'New'
                        : null
                    )
                    ->extraAttributes(['class' => 'text-[11px]'])
                    ->tooltip(fn($state) => $state ? 'Tooltip' : null),

                TextColumn::make('ticket_number')
                    ->label('Ticket Number')
                    ->searchable(),

                TextColumn::make('title')
                    ->weight(fn(Model $record) => (method_exists($record, 'isNotOpened') && $record->isNotOpened()) ? 'bold' : 'medium')
                    ->searchable(query: function ($query, string $search) {
                        return $query->where(function ($q) use ($search) {
                            $q->where('ticket_number', 'like', "%{$search}%")
                                ->orWhereRaw("JSON_EXTRACT(custom_fields, '$.*') LIKE ?", ["%{$search}%"]);
                        });
                    })
                    ->limit(40)
                    ->tooltip(fn(Ticket $record): string => $record->title),

                TextColumn::make('createdBy.name')
                    ->sortable(),

                TextColumn::make('priority')
                    ->badge(),

                TextColumn::make('company.name')
                    ->searchable(),

                TextColumn::make('status.name')
                    ->label('Status')
                    ->formatStateUsing(fn($record) => $record->status?->name ?
                        "<span style='
                                display: inline-flex;
                                align-items: center;
                                background-color: {$record->status->color}10;
                                color: {$record->status->color};
                                padding: 0.3rem 0.8rem;
                                border-radius: 9999px;
                                font-size: 0.7rem;
                                font-weight: 600;
                                line-height: 1;
                                border: 1.5px solid {$record->status->color};
                                white-space: nowrap;
                            '>{$record->status->name}</span>"
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

                SelectFilter::make('status')
                    ->label('Status')
                    ->relationship('status', 'name')
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
