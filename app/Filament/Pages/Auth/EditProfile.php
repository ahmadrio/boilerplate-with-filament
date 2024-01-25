<?php declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Exception;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use JetBrains\PhpStorm\NoReturn;

/**
 * @property Form $form
 */
class EditProfile extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'filament.pages.auth.edit-profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function getBreadcrumbs(): array
    {
        return [
            Filament::getHomeUrl() => __('filament-panels::pages/dashboard.title'),
            static::getNavigationGroup() => __('filament-panels::pages/auth/edit-profile.label'),
        ];
    }

    public function getTitle(): string|Htmlable
    {
        return __('Update :name', [
            'name' => __('filament-panels::pages/auth/edit-profile.label'),
        ]);
    }

    /**
     * @throws Exception
     */
    public function mount(): void
    {
        $this->fillForms();
    }

    /**
     * @param  Form  $form
     *
     * @return Form
     *
     * @throws Exception
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('Profile Information'))
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(trans('resources/globals.filament.name'))
                            ->autofocus()
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->dehydrated(false)
                            ->disabled()
                            ->helperText('This email has been verified.'),
                    ])
                    ->columns(),

                Forms\Components\Section::make(__('Update :name', ['name' => __('resources/globals.filament.password')]))
                    ->schema([
                        Forms\Components\TextInput::make('current_password')
                            ->label(__('Enter the current password'))
                            ->password()
                            ->currentPassword()
                            ->requiredWithAll('password,password_confirmation')
                            ->dehydrated(false),

                        Forms\Components\TextInput::make('password')
                            ->label(__('Enter the new password'))
                            ->password()
                            ->same('password_confirmation')
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                            ->dehydrated(fn ($state) => filled($state))
                            ->live(debounce: 500),

                        Forms\Components\TextInput::make('password_confirmation')
                            ->label(__('Enter the password again'))
                            ->password()
                            ->requiredWithAll('current_password,password')
                            ->dehydrated(false),
                    ])
                    ->columns(),
            ])
            ->model($this->getUser())
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label(__('filament-panels::pages/auth/edit-profile.form.actions.save.label'))
                ->action('save'),
        ];
    }

    /**
     * @throws Exception
     */
    #[NoReturn]
    public function save(): void
    {
        $data = $this->form->getState();
        $this->getUser()->update($data);
        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_' . Filament::getAuthGuard() => $data['password'],
            ]);
        }

        Notification::make()
            ->success()
            ->title(__('filament-actions::edit.single.notifications.saved.title'))
            ->send();
    }

    /**
     * @throws Exception
     */
    protected function getUser(): Authenticatable|Model
    {
        $user = Filament::auth()->user();
        if ( ! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    /**
     * @throws Exception
     */
    protected function fillForms(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->form->fill($data);
    }
}
