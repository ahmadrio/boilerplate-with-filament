<?php declare(strict_types=1);

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(trans('resources/globals.filament.name')),

                        Infolists\Components\TextEntry::make('email'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label(trans('resources/globals.filament.created_at'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label(trans('resources/globals.filament.updated_at'))
                            ->dateTime(),

                        Infolists\Components\TextEntry::make('deleted_at')
                            ->label(trans('resources/globals.filament.deleted_at'))
                            ->placeholder('-')
                            ->dateTime(),
                    ])
                    ->columns(2),
            ]);
    }
}
