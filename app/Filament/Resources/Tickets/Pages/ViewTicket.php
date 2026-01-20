<?php

namespace App\Filament\Resources\Tickets\Pages;

use App\Enums\TicketPriority;
use App\Filament\Resources\Tickets\TicketResource;
use App\Models\TicketStatus;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\Select;
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

            Action::make('assignTicket')
                ->label('Assign Ticket')
                ->icon('heroicon-o-user-plus')
                ->color('gray')
                ->button()
                ->visible(fn() => $this->canAssignTicket())
                ->schema([
                    Select::make('assignee')
                        ->label('Select Assignee')
                        ->searchable()
                        ->getOptionLabelUsing(function ($value) {
                            $userModel = User::class;
                            $user = $userModel::find($value);
                            return $user?->name;
                        })
                        ->options(function () {
                            $userModel = User::class;
                            
                            return $userModel::where('company_id', Auth::user()->company_id)
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn($user) => [$user->getKey() => $user->name])
                                ->toArray();
                        })
                        ->default($this->record->assigned_to)
                        ->native(false)
                ])
                ->action(function (array $data) {
                    $this->assignTicket($data['assignee']);
                }),

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

    protected function canAssignTicket(): bool
    {
        return Auth::user()->can('assign', $this->record);
    }

    public function assignTicket($assigneeId): void
    {
        if (!$this->canAssignTicket()) {
            Notification::make()->title('Unauthorized')
                ->danger()
                ->body('You do not have permission to assign tickets.')
                ->send();
            return;
        }

        $oldAssigneeId = $this->record->assigned_to;
        $newAssigneeId = $assigneeId;

        $this->record->update(['assigned_to' => $newAssigneeId]);

        $this->record->markOpenedBy(Auth::user()->id);

        Notification::make()->title('Ticket assigned')
            ->success()
            ->send();

        $this->dispatch('$refresh');
    }
}
