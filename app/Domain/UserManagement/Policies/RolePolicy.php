<?php declare(strict_types=1);

namespace App\Domain\UserManagement\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(['roles.read']);
    }

    public function view(User $user): bool
    {
        return $user->can(['roles.read']);
    }

    public function create(User $user): bool
    {
        return $user->can(['roles.create']);
    }

    public function update(User $user): bool
    {
        return $user->can(['roles.update']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(['roles.delete']);
    }

    public function delete(User $user): bool
    {
        return $user->can(['roles.delete']);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can(['roles.delete']);
    }

    public function restore(User $user): bool
    {
        return $user->can(['roles.delete']);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can(['roles.delete']);
    }

    public function forceDelete(User $user): bool
    {
        return $user->can(['roles.delete']);
    }
}
