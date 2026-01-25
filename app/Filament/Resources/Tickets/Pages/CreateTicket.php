<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;
    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        $data['created_by'] = $user->id;
        $data['company_id'] = $user->company_id;
        $data['to_main'] = (bool)$user->company?->is_main;
        $data['ticket_ulid'] = Str::ulid();
        $data['slug'] = Str::slug($data['title']);

        return $data;
    }
}
