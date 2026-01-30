<?php

namespace App\Filament\Resources\Companies\Pages;

use App\Filament\Resources\Companies\CompanyResource;
use App\Filament\Resources\Tickets\TicketResource;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ManageCompanyTickets extends ManageRelatedRecords
{
    protected static string $resource = CompanyResource::class;

    protected static string $relationship = 'tickets';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    public function table(Table $table): Table
    {
        return TicketResource::table($table)
            ->headerActions([
                CreateAction::make()
                    ->label('New Ticket')
                    ->createAnother(false),
            ]);
    }
}
