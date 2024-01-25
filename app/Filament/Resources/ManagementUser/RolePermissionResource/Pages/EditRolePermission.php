<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser\RolePermissionResource\Pages;

use App\Domain\UserManagement\Models\Role;
use App\Filament\Resources\ManagementUser\RolePermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditRolePermission extends EditRecord
{
    protected static string $resource = RolePermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $permissions = [];
        $getPermissions = Role::findById($data['id'])->permissions;
        if (count($getPermissions) > 0) {
            foreach ($getPermissions as $permission) {
                $resource = explode('.', $permission->name)[0];
                $event = explode('.', $permission->name)[1];
                $permissions[$resource][$event] = true;
            }

            $data['permissions'] = $permissions;
        }

        return $data;
    }

    protected function handleRecordUpdate(Role|Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Role|Model {
            $permissions = [];
            foreach ($data['permissions'] as $resource => $resourceValues) {
                foreach ($resourceValues as $permission => $value) {
                    if ($value) {
                        $permissions[] = "{$resource}.{$permission}";
                    }
                }
            }
            $record->syncPermissions($permissions);
            $record->update([
                'name' => $data['name'],
            ]);

            return $record;
        });
    }
}
