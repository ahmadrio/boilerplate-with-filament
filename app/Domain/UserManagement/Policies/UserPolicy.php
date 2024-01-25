<?php declare(strict_types=1);

namespace App\Domain\UserManagement\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(['users.read']);
    }

    public function view(User $user): bool
    {
        return $user->can(['users.read']);
    }

    public function create(User $user): bool
    {
        return $user->can(['users.create']);
    }

    public function update(User $user): bool
    {
        return $user->can(['users.update']);
    }

    public function deleteAny(User $user): bool
    {
        return $user->can(['users.delete']);
    }

    public function delete(User $user): bool
    {
        return $user->can(['users.delete']);
    }

    public function restoreAny(User $user): bool
    {
        return $user->can(['users.delete']);
    }

    public function restore(User $user): bool
    {
        return $user->can(['users.delete']);
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->can(['users.delete']);
    }

    public function forceDelete(User $user): bool
    {
        return $user->can(['users.delete']);
    }
}
