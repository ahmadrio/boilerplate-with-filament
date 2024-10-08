<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser\UserResource\Pages;

use App\Filament\Resources\ManagementUser\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
