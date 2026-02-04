<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\TicketStatus;
use Filament\Actions\CreateAction;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;

class ListTickets extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = TicketResource::class;

    public function getTabs(): array
    {
        $tabs = [
            null => Tab::make('All'),
        ];

        TicketStatus::query()
            ->orderBy('order_column')
            ->get()
            ->each(function (TicketStatus $status) use (&$tabs) {
                $tabs[$status->slug ?? str($status->name)->slug()->toString()] = Tab::make($status->name)
                    ->query(fn($query) => $query->where('ticket_status_id', $status->id));
            });

        return $tabs;
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
