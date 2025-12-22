<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ExtraCharge;
use Illuminate\Auth\Access\HandlesAuthorization;

class ExtraChargePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ExtraCharge');
    }

    public function view(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('View:ExtraCharge');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ExtraCharge');
    }

    public function update(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('Update:ExtraCharge');
    }

    public function delete(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('Delete:ExtraCharge');
    }

    public function restore(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('Restore:ExtraCharge');
    }

    public function forceDelete(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('ForceDelete:ExtraCharge');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ExtraCharge');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ExtraCharge');
    }

    public function replicate(AuthUser $authUser, ExtraCharge $extraCharge): bool
    {
        return $authUser->can('Replicate:ExtraCharge');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ExtraCharge');
    }

}