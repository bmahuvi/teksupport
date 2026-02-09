<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use Filament\Actions\CreateAction;
use Filament\Facades\Filament;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTickets extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TicketResource::class;

    public function getTabs(): array
    {
        $user = Filament::auth()->user();
        $ticketQuery = Ticket::query();
        if (!$user->company?->is_main) {
            $ticketQuery->where('company_id', $user->company_id);
        }

        return [
            'all' => Tab::make('All')
                ->badge($ticketQuery->whereHas('status', fn($q) => $q->where('is_closing_status', false))->count())
                ->icon('heroicon-o-ticket'),

            'my_tickets' => Tab::make('My Tickets')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('created_by', $user->getKey())
                )
                ->badge($ticketQuery->where('created_by', $user->getKey())
                    ->count())
                ->icon('heroicon-o-user'),

            'open' => Tab::make('Open')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('status', fn(Builder $q) => $q->where('is_closing_status', false))
                )
                ->badge($ticketQuery->whereHas('status', fn($q) => $q->where('is_closing_status', false))->count())
                ->icon('heroicon-o-clock'),

            'unassigned' => Tab::make('Unassigned')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereNull('assigned_to')
                    ->whereHas('status', fn(Builder $q) => $q->where('is_closing_status', false))
                )
                ->badge($ticketQuery->whereNull('assigned_to')
                    ->whereHas('status', fn($q) => $q->where('is_closing_status', false))
                    ->count())
                ->icon('heroicon-o-user-minus'),

            'closed' => Tab::make('Closed')
                ->modifyQueryUsing(fn(Builder $query) => $query->whereHas('status', fn(Builder $q) => $q->where('is_closing_status', true))
                )
                ->badge($ticketQuery->whereHas('status', fn($q) => $q->where('is_closing_status', true))->count())
                ->icon('heroicon-o-check-circle'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return TicketResource::getWidgets();
    }

}
