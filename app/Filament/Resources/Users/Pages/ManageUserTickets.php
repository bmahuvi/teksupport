<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\BulkActionGroup;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables\Table;

class ManageUserTickets extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'tickets';

    protected static ?string $relatedResource = TicketResource::class;

    public static function getNavigationBadge(): ?string
    {
        return \Livewire::current()->getRecord()->tickets->count();
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordActions([])
            ->headerActions([])
            ->toolbarActions([
                BulkActionGroup::make([]),
            ]);
    }
}
