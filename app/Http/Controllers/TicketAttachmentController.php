<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class TicketAttachmentController extends Controller
{
    public function show($ticketId, $filename)
    {
        $user = $this->resolveAuthenticatedUser();

        if (!$user) {
            return $this->unauthorizedAccess($ticketId);
        }

        $ticket = Ticket::findOrFail($ticketId);

        if (!$this->userHasAccessToTicket($user, $ticket)) {
            return $this->unauthorizedAccess($ticketId, $user);
        }

        $path = "ticket-attachments/{$ticketId}/{$filename}";

        if (!$this->fileExists($path)) {
            Log::error('File not found in storage', [
                'path' => $path,
                'disk' => 'private'
            ]);
            abort(404, 'File not found');
        }

        return $this->streamFile($path);
    }

    private function resolveAuthenticatedUser()
    {
        $user = auth()->user();

        if (!$user && class_exists(Filament::class)) {
            $user = Filament::auth()->user();
        }

        return $user;
    }

    private function unauthorizedAccess($ticketId, $user = null)
    {
        if ($user) {
            Log::warning('Unauthorized attachment access', [
                'ticketId' => $ticketId,
                'userId' => $user->getKey()
            ]);
            abort(403, 'Unauthorized');
        }

        Log::warning('Unauthorized attachment access - no user', ['ticketId' => $ticketId]);
        return redirect('/admin/login');
    }

    private function userHasAccessToTicket($user, $ticket)
    {
        if ($user->getKey() == $ticket->created_by || $user->getKey() == $ticket->assigned_to) {
            return true;
        }

        if ($this->userIsSuperAdmin($user)) {
            return true;
        }
    }

    private function userIsSuperAdmin($user)
    {

        return $user->hasRole('super-admin');
    }

    private function fileExists($path)
    {
        return Storage::disk('private')->exists($path);
    }

    private function streamFile($path)
    {
        $disk = Storage::disk('private');
        $filePath = $disk->path($path);
        $mimeType = $disk->mimeType($path);

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'private, max-age=3600',
        ]);
    }

}
