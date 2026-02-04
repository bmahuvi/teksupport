<?php

namespace App\Filament\Resources\Tickets\Widgets;

use App\Filament\Resources\Tickets\Pages\ListTickets;
use App\Models\Ticket;
use Filament\Widgets\Concerns\InteractsWithPageTable;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class TicketStats extends StatsOverviewWidget
{
    use InteractsWithPageTable;

    protected ?string $pollingInterval = null;

    protected function getTablePage(): string
    {
        return ListTickets::class;
    }

    protected function getStats(): array
    {
        $ticketsData = Trend::model(Ticket::class)
            ->between(start: now()->subYear(), end: now())
            ->perMonth()
            ->count();

        return [
            Stat::make('Tickets', $this->getPageTableQuery()->count())
                ->chart(
                    $ticketsData
                        ->map(fn(TrendValue $value) => $value->aggregate)
                        ->toArray()
                ),

            Stat::make('Open Tickets',
                $this->getPageTableQuery()
                    ->whereHas('status', fn($q) => $q->whereNotIn('name', ['Closed']))
                    ->count()
            ),

            Stat::make('Closed Tickets',
                $this->getPageTableQuery()
                    ->whereHas('status', fn($q) => $q->where('name', 'Closed'))
                    ->count()
            ),
        ];
    }
}
