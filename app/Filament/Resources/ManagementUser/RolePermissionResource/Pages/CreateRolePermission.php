<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser\RolePermissionResource\Pages;

use App\Filament\Resources\ManagementUser\RolePermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRolePermission extends CreateRecord
{
    protected static string $resource = RolePermissionResource::class;
}
