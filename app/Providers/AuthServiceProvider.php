<?php declare(strict_types=1);

namespace App\Providers;

use App\Domain\UserManagement\Models\Role;
use App\Domain\UserManagement\Policies\RolePolicy;
use App\Domain\UserManagement\Policies\UserPolicy;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::before(fn (User $user): ?bool => $user->hasRole('Super Admin') ? true : null);
    }
}
