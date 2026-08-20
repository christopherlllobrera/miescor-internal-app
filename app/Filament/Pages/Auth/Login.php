<?php

namespace App\Filament\Pages\Auth;

use App\Models\User;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use SensitiveParameter;

class Login extends BaseLogin
{
    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('comp_email')
            ->label('Company Email')
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(#[SensitiveParameter] array $data): array
    {
        return [
            'comp_email' => $data['comp_email'] ?? null,
            'password' => $data['password'] ?? null,
        ];
    }

    protected function throwFailureValidationException(): never
    {
        $data = $this->form->getState();

        $email = $data['comp_email'] ?? null;
        $password = $data['password'] ?? null;

        // Try to find the user by company email
        $user = User::where('comp_email', $email)->first();

        if (! $user) {
            // Email not found
            throw ValidationException::withMessages([
                'data.comp_email' => 'This company email does not match our records.',
            ]);
        }

        if (! Hash::check($password, $user->password)) {
            // Password incorrect
            throw ValidationException::withMessages([
                'data.password' => 'The password you entered is incorrect.',
            ]);
        }

        throw ValidationException::withMessages([
            'data.comp_email' => __('filament-panels::auth/pages/login.messages.failed'),
        ]);
    }
}
