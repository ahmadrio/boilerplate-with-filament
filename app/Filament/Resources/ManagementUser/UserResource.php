<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser;

use App\Filament\Resources\ManagementUser\UserResource\RelationManagers\RolesRelationManager;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getNavigationGroup(): ?string
    {
        return trans('resources/globals.filament.management-users');
    }

    public static function getModelLabel(): string
    {
        return trans('resources/globals.filament.users');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('resources/globals.filament.name'))
                            ->autofocus()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        // ref: https://v2.filamentphp.com/tricks/password-form-fields
                        Forms\Components\TextInput::make('password')
                            ->label(trans('resources/globals.filament.password'))
                            ->password()
                            ->autocomplete(false)
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->confirmed()
                            ->live(debounce: 500),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label(trans('resources/globals.filament.password_confirmation'))
                            ->password()
                            ->autocomplete(false)
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])
                    ->columns(),
            ]);
    }

    /**
     * @param  Table  $table
     *
     * @return Table
     *
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->deferLoading()
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('id', '!=', auth()->id()))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(trans('resources/globals.filament.name'))
                    ->description(fn (User $record): string => $record->getRoleNames()->implode(' | '))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('resources/globals.filament.created_at'))
                    ->searchable()
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => UserResource\Pages\ListUsers::route('/'),
            'create' => UserResource\Pages\CreateUser::route('/create'),
            'view' => UserResource\Pages\ViewUser::route('/{record}'),
            'edit' => UserResource\Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
