<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Ticket;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestTickets extends TableWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return TicketResource::table($table)
            ->query(TicketResource::getEloquentQuery())
            ->defaultSort('created_at', 'desc')
            ->defaultPaginationPageOption(5)
            ->recordActions([
                Action::make('edit')
                    ->hiddenLabel()
                    ->icon('tabler-pencil')
                    ->url(fn(Ticket $record): string => TicketResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
