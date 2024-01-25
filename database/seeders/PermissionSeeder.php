<?php declare(strict_types=1);

namespace Database\Seeders;

use App\Domain\UserManagement\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resources = [
            'users',
            'roles',
        ];

        $permissions = [
            'create',
            'read',
            'update',
            'delete',
        ];

        foreach ($resources as $resource) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate([
                    'name' => "{$resource}.{$permission}",
                ]);
            }
        }
    }
}
