<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser\RolePermissionResource\Pages;

use App\Filament\Resources\ManagementUser\RolePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRolesPermission extends ListRecords
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
