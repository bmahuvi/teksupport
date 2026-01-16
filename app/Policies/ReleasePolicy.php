<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Release;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReleasePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Release');
    }

    public function view(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('View:Release');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Release');
    }

    public function update(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('Update:Release');
    }

    public function delete(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('Delete:Release');
    }

    public function restore(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('Restore:Release');
    }

    public function forceDelete(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('ForceDelete:Release');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Release');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Release');
    }

    public function replicate(AuthUser $authUser, Release $release): bool
    {
        return $authUser->can('Replicate:Release');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Release');
    }

}