<?php

namespace App\Livewire;

use App\Models\Form;
use App\Models\Ticket;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

class TicketSubmitForm extends Component
{
    use WithFileUploads;

    #[Url(as: 'tab', except: 'new')]
    public $activeTab = 'new';

    #[Url(as: 'ticket', except: '')]
    public $urlTicketId = '';

    public $form_id;
    public $custom_fields = [];
    public $form_fields = [];
    public $available_forms = [];
    public $userTickets = [];
    public $showForm = true;
    public $selectedTicket = null;

    public function mount(): void
    {
        $this->loadUserTickets();

        if ($this->urlTicketId) {
            $this->viewTicket($this->urlTicketId);
        } elseif ($this->activeTab === 'list') {
            $this->showList();
        } else {
            $this->showNewTicketForm();
        }
    }

    protected function loadUserTickets(): void
    {
        if (auth()->check()) {
            $this->userTickets = Ticket::where('user_id', auth()->id())
                ->with(['status', 'replies'])
                ->orderBy('last_activity_at', 'desc')
                ->get();
        }
    }

    public function viewTicket($ticketId): void
    {
        $this->selectedTicket = Ticket::with(['status', 'replies.user'])
            ->where('id', $ticketId)
            ->where('created_by', auth()->id())
            ->first();

        if ($this->selectedTicket) {
            $this->activeTab = 'view';
            $this->urlTicketId = $ticketId;
            $this->showForm = false;

            foreach ($this->selectedTicket->replies as $r) {
                $r->markOpenedBy(auth()->id());
            }
            $this->selectedTicket->markOpenedBy(auth()->id());
        } else {
            $this->backToList();
        }
    }

    public function backToList(): void
    {
        $this->loadUserTickets();
        $this->showList();
    }

    public function showList(): void
    {
        $this->activeTab = 'list';
        $this->urlTicketId = '';
        $this->showForm = false;
        $this->selectedTicket = null;
    }

    public function showNewTicketForm(): void
    {
        $this->activeTab = 'new';
        $this->urlTicketId = '';
        $this->showForm = true;
        $this->selectedTicket = null;
    }

    public function updatedFormId(): void
    {
        $this->custom_fields = [];
        $this->loadFormFields();
    }

    protected function loadFormFields(): void
    {
        if (!$this->form_id) {
            $this->form_fields = [];
            return;
        }
        $form = Form::with('fields')->find($this->form_id);
        $this->form_fields = $form && $form->fields->count() ? $form->fields->toArray() : [];
    }

    public function render()
    {
        return view('livewire.ticket-submit-form');
    }
}
