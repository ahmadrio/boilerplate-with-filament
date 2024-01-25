<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser\UserResource\Pages;

use App\Filament\Resources\ManagementUser\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;
}
