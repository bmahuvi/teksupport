<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Route;
use Livewire\Component;

class TicketAttachmentsDisplay extends Component
{
    public $ticketId;
    public $files;
    public $label;

    public function mount($ticketId, $files, $label = null): void
    {
        $this->ticketId = $ticketId;
        $this->files = is_array($files) ? $files : [$files];
        $this->label = $label;
    }

    public function getFileUrl($file): string
    {
        if (!is_string($file)) {
            return '';
        }

        $filename = basename($file);

        if (Route::has('attachment')) {
            return route('attachment', [
                'ticketId' => $this->ticketId,
                'filename' => $filename
            ]);
        }

        return url('/private/ticket-attachments/' . $this->ticketId . '/' . $filename);
    }

    public function isImage($file): bool
    {
        if (!is_string($file)) {
            return false;
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'svg']);
    }

    public function render()
    {
        return view('livewire.ticket-attachments-display');
    }
}
