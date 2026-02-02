<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Livewire\WithPagination;

class TicketTimeline extends Component implements HasForms
{
    use InteractsWithForms, WithPagination;

    public $ticket;
    public $limit = null;
    public $perPage = 10;
    protected $listeners = [
        'activity-added' => 'refreshTimeline',
    ];

    public function mount($ticket, $limit = null): void
    {
        $this->ticket = $ticket;
        $this->limit = $limit;
    }

    public function getActivitiesProperty()
    {
        $query = $this->ticket->activities()
            ->with('user')
            ->orderBy('created_at', 'desc');

        if ($this->limit) {
            return $query->limit($this->limit)->get();
        }

        return $query->simplePaginate($this->perPage);
    }

    public function refreshTimeline(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.ticket-timeline', [
            'activities' => $this->activities
        ]);
    }
}
