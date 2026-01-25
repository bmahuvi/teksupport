<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class TicketStatWidget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Tickets', Ticket::count())
                ->description('All tickets in the system')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('primary'),

            Stat::make(
                'Open Tickets',
                Ticket::whereHas('ticketStatus', fn(Builder $query) => $query->where('is_closing_status', false))->count()
            )
                ->description('Tickets that require attention')
                ->descriptionIcon('heroicon-m-fire')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('warning'),
            Stat::make(
                'Closed Tickets',
                Ticket::whereHas('ticketStatus', fn(Builder $query) => $query->where('is_closing_status', true))->count()
            )
                ->description('Successfully resolved tickets')
                ->descriptionIcon('heroicon-m-check-badge')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('success'),

        ];
    }
}
