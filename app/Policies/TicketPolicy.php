<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Ticket');
    }

    public function view(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('View:Ticket');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Ticket');
    }

    public function update(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Update:Ticket');
    }

    public function delete(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Delete:Ticket');
    }

    public function restore(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Restore:Ticket');
    }

    public function forceDelete(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('ForceDelete:Ticket');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Ticket');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Ticket');
    }

    public function replicate(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Replicate:Ticket');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Ticket');
    }

    public function changeStatus(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('ChangeStatus:Ticket');
    }

    public function changePriority(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('ChangePriority:Ticket');
    }

    public function assign(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Assign:Ticket');
    }

    public function reply(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Reply:Ticket');
    }

    public function approve(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Approve:Ticket');
    }

    public function cancel(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Cancel:Ticket');
    }

    public function close(AuthUser $authUser, Ticket $ticket): bool
    {
        return $authUser->can('Close:Ticket');
    }

}