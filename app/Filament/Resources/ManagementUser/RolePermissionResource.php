<?php declare(strict_types=1);

namespace App\Filament\Resources\ManagementUser;

use App\Domain\UserManagement\Models\Permission;
use App\Domain\UserManagement\Models\Role;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class RolePermissionResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    public static function getNavigationGroup(): ?string
    {
        return trans('resources/globals.filament.management-users');
    }

    public static function getModelLabel(): string
    {
        return trans('resources/globals.filament.roles') . ' & ' . trans('resources/globals.filament.permissions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('resources/globals.filament.name'))
                            ->required(),
                    ]),

                Forms\Components\Section::make(trans('resources/globals.filament.permissions'))
                    ->description(__('Choose the permissions for this role'))
                    ->schema(self::permissionLists())
                    ->collapsed(false)
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
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label(trans('resources/globals.filament.name')),

                Tables\Columns\TextColumn::make('permissions_count')
                    ->label(trans('resources/globals.filament.permissions_count'))
                    ->counts('permissions')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label(trans('resources/globals.filament.users_count'))
                    ->counts('users')
                    ->badge()
                    ->color(fn (string $state): string => $state > 0 ? 'success' : 'danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label(trans('resources/globals.filament.created_at'))
                    ->date(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => RolePermissionResource\Pages\ListRolesPermission::route('/'),
            'create' => RolePermissionResource\Pages\CreateRolePermission::route('/create'),
            'edit' => RolePermissionResource\Pages\EditRolePermission::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    private static function permissionLists(): array
    {
        $groups = [];
        $fields = [];
        $permissions = Permission::all();

        foreach ($permissions as $permission) {
            $group = explode('.', $permission->name)[0];
            $label = Str::title(explode('.', $permission->name)[1]);
            $groups[$group][] = Forms\Components\Checkbox::make('permissions.' . $permission->name)->label($label);
        }

        foreach ($groups as $group => $permissions) {
            $fields[] = Forms\Components\Fieldset::make(trans("resources/globals.filament.{$group}"))
                ->schema($permissions)
                ->columnSpan(1);
        }

        return $fields;
    }
}
