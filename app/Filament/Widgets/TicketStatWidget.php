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
            Stat::make('Total Tickets', function () {
                $user = auth()->user();

                if (!$user) {
                    return 0;
                }

                $query = Ticket::query();

                if ($user->company?->is_main) {
                    return $query->count();
                }

                return $query->where('company_id', $user->company_id)->count();
            })
                ->description('All tickets in the system')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('primary'),

            Stat::make(
                'Open Tickets', function () {
                $user = auth()->user();

                if (!$user) {
                    return 0;
                }

                $query = Ticket::whereHas('status', fn(Builder $q) => $q->where('is_closing_status', false));

                if ($user->company?->is_main) {
                    return $query->count();
                }

                return $query->where('company_id', $user->company_id)->count();
            }
            )
                ->description('Tickets that require attention')
                ->descriptionIcon('heroicon-m-fire')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('warning'),
            Stat::make(
                'Closed Tickets', function () {
                $user = auth()->user();

                if (!$user) {
                    return 0;
                }

                $query = Ticket::whereHas('status', fn(Builder $q) => $q->where('is_closing_status', true));

                if ($user->company?->is_main) {
                    return $query->count();
                }

                return $query->where('company_id', $user->company_id)->count();
            }
            )
                ->description('Successfully resolved tickets')
                ->descriptionIcon('heroicon-m-check-badge')
                ->descriptionIcon('heroicon-m-ticket', position: IconPosition::Before)
                ->color('success'),

        ];
    }
}
