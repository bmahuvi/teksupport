<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\TicketPriority;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\TicketStatus;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Auth;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        $statusActions = [];
        if ($this->canChangeStatus()) {
            foreach (TicketStatus::all() as $status) {
                $statusActions[] = Action::make('status_' . $status->id)
                    ->label($status->name)
                    ->visible(fn($record) => $record->ticket_status_id !== $status->id)
                    ->action(fn() => $this->changeStatus($status->id));
            }
        }

        $priorityActions = [];
        if ($this->canChangePriority()) {
            foreach (TicketPriority::cases() as $priority) {
                $priorityActions[] = Action::make('priority_' . $priority->value)
                    ->label($priority->getLabel())
                    ->color($priority->getColor())
                    ->visible(fn($record) => $record->priority !== $priority)
                    ->action(fn() => $this->changePriority($priority->value));
            }
        }

        return [
            ActionGroup::make($statusActions)
                ->label('Change Status')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canChangeStatus()),

            ActionGroup::make($priorityActions)
                ->label('Change Priority')
                ->icon('heroicon-o-flag')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canChangePriority()),

            Action::make('delete')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->visible(fn() => Auth::user()->can('delete', $this->record))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->delete();
                    return redirect()->to(TicketResource::getUrl('index'));
                }),
        ];
    }

    protected function canChangeStatus(): bool
    {
        return Auth::user()->can('changeStatus', $this->record);
    }

    public function changeStatus($statusId): void
    {
        if (!$this->canChangeStatus()) {
            Notification::make()
                ->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to change ticket status.')
                ->send();
            return;
        }
        $this->record->update(['ticket_status_id' => $statusId]);

        Notification::make()->title('Status updated.')->success()->send();

        $this->dispatch('$refresh');

    }

    protected function canChangePriority(): bool
    {
        return Auth::user()->can('changePriority', $this->record);
    }

    public function changePriority($priority): void
    {
        if (!$this->canChangePriority()) {
            Notification::make()
                ->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to change ticket priority.')
                ->send();
            return;
        }

        $oldPriority = $this->record->priority;
        $this->record->update(['priority' => $priority]);

        Notification::make()
            ->title('Priority updated')
            ->success()
            ->send();

        $this->dispatch('$refresh');
    }
}
