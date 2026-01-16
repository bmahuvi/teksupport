<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Filament\Resources\Tickets\TicketResource;
use App\Models\Category;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

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

        if (!empty($data['category_id'])) {
            $data['requires_approval'] = Category::whereKey($data['category_id'])
                ->value('requires_approval') ?? false;

            $data['status'] = $data['requires_approval']
                ? 'Waiting Approval'
                : 'New';
        } else {
            $data['requires_approval'] = false;
            $data['status'] = 'New';
        }

        return $data;
    }
}
