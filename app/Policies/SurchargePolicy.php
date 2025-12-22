<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Surcharge;
use Illuminate\Auth\Access\HandlesAuthorization;

class SurchargePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Surcharge');
    }

    public function view(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('View:Surcharge');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Surcharge');
    }

    public function update(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('Update:Surcharge');
    }

    public function delete(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('Delete:Surcharge');
    }

    public function restore(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('Restore:Surcharge');
    }

    public function forceDelete(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('ForceDelete:Surcharge');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Surcharge');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Surcharge');
    }

    public function replicate(AuthUser $authUser, Surcharge $surcharge): bool
    {
        return $authUser->can('Replicate:Surcharge');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Surcharge');
    }

}