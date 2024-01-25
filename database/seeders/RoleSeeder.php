<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\UserManagement\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'Super Admin',
            'Admin',
            'User',
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate([
                'name' => $role,
            ]);
        }
    }
}
