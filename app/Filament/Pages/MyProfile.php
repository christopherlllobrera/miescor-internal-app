<?php

namespace App\Filament\Pages;

use App\Models\User;
use BackedEnum;
use Filament\Auth\MultiFactor\Contracts\MultiFactorAuthenticationProvider;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MyProfile extends Page
{
    protected string $view = 'filament.pages.my-profile';

    protected static ?string $slug = 'profile';

    protected static ?string $title = 'Profile';

    // protected static ?string $navigationLabel = 'Profile';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?int $navigationSort = 10;

    public ?array $profileData = [];

    public ?array $passwordData = [];

    public function mount(): void
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        $this->editProfileForm->fill([
            'avatar_url' => $user->avatar_url,
            'username' => $user->username,
            'comp_email' => $user->comp_email,
        ]);

        $this->editPasswordForm->fill();
    }

    protected function getForms(): array
    {
        $forms = ['editProfileForm', 'editPasswordForm'];

        if (Filament::hasMultiFactorAuthentication()) {
            $forms[] = 'mfaSchema';
        }

        return $forms;
    }

    public function editProfileForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile Information')
                    ->aside()
                    ->description('Update your account profile information.')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Avatar')
                            ->avatar()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('avatars')
                            ->rules(['max:1024']),
                        TextInput::make('username')
                            ->label('Name')
                            ->required(),
                        TextInput::make('comp_email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(User::class, 'comp_email', ignorable: fn () => Filament::auth()->user()),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function editPasswordForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Update Password')
                    ->aside()
                    ->description('Ensure your account is using a long, random password to stay secure.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Current Password')
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->live(debounce: 500)
                            ->same('passwordConfirmation')
                            ->revealable(),
                        TextInput::make('passwordConfirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->dehydrated(false)
                            ->revealable(),
                    ]),
            ])
            ->model(Filament::auth()->user())
            ->statePath('passwordData');
    }

    public function mfaSchema(Schema $schema): Schema
    {
        /** @var User $user */
        $user = Filament::auth()->user();

        return $schema
            ->components([
                Section::make('Multi-Factor Authentication')
                    ->aside()
                    ->description('Add additional security to your account using multi-factor authentication.')
                    ->schema([
                        Section::make()
                            ->compact()
                            ->divided()
                            ->secondary()
                            ->schema(
                                collect(Filament::getMultiFactorAuthenticationProviders())
                                    ->sort(fn (MultiFactorAuthenticationProvider $provider): int => $provider->isEnabled($user) ? 0 : 1)
                                    ->map(fn (MultiFactorAuthenticationProvider $provider) => Group::make($provider->getManagementSchemaComponents())
                                        ->statePath($provider->getId()))
                                    ->all()
                            ),
                    ]),
            ]);
    }

    public function updateProfile(): void
    {
        try {
            $data = $this->editProfileForm->getState();

            /** @var User $user */
            $user = Filament::auth()->user();
            $user->update($data);

            $this->dispatch('refresh-topbar');
        } catch (Halt $exception) {
            return;
        }

        Notification::make()
            ->success()
            ->title('Profile updated successfully.')
            ->send();
    }

    public function updatePassword(): void
    {
        try {
            $data = $this->editPasswordForm->getState();

            /** @var User $user */
            $user = Filament::auth()->user();
            $user->update([
                'password' => $data['password'],
            ]);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->editPasswordForm->fill();

        Notification::make()
            ->success()
            ->title('Password updated successfully.')
            ->send();
    }
}
