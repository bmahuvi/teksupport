<?php

namespace App\Livewire;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Attributes\On;
use Livewire\Component;

class TicketChatMessages extends Component implements HasForms
{
    use InteractsWithForms;

    public $ticket;
    public $replies;

    public function mount($ticket): void
    {
        $this->ticket = $ticket;
        $this->loadReplies();
    }

    public function loadReplies(): void
    {
        $this->replies = $this->ticket->replies()
            ->with('user', 'ticket')
            ->orderBy('created_at', 'asc')
            ->get();

        foreach ($this->replies as $reply) {
            if (!$reply->is_opened && auth()->check()) {
                $reply->markOpenedBy(auth()->id());
            }
        }
    }

    #[On('$refresh')]
    public function refresh(): void
    {
        $this->loadReplies();
        $this->dispatch('scroll-to-bottom');
    }

    public function render()
    {
        $this->loadReplies();
        return view('livewire.ticket-chat-messages');
    }
}
