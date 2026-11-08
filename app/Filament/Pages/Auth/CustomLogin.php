<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Notifications\Notification;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    public function mount(): void
    {
        parent::mount();

        if ($message = session('filament-socialite-login-error')) {
            Notification::make()
                ->title('Login Google gagal')
                ->body($message)
                ->danger()
                ->send();
        }
    }

    public function getHeading(): string|Htmlable
    {
        return 'Selamat Datang Kembali';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Masuk ke Admin Panel untuk mengelola Website Portofolio & CV Anda.';
    }

    public function authenticate(): ?LoginResponse
    {
        try {
            $response = parent::authenticate();
        } catch (ValidationException $exception) {
            $message = $this->buildAuthNotificationMessage($exception->errors());

            Notification::make()
                ->title('Autentikasi gagal')
                ->body($message)
                ->danger()
                ->send();

            throw $exception;
        }

        if ($response !== null) {
            Notification::make()
                ->title('Login berhasil')
                ->body('Anda berhasil masuk ke admin panel.')
                ->success()
                ->send();
        }

        return $response;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function getCredentialsFromFormData(#[\SensitiveParameter] array $data): array
    {
        return [
            ...parent::getCredentialsFromFormData($data),
            'role' => 'admin',
        ];
    }

    protected function buildAuthNotificationMessage(array $errors): string
    {
        $messages = [];

        if (isset($errors['data.email'])) {
            $messages[] = implode(' ', $errors['data.email']);
        }

        if (isset($errors['data.password'])) {
            $messages[] = implode(' ', $errors['data.password']);
        }

        if (empty($messages)) {
            $messages[] = __('filament-panels::auth/pages/login.messages.failed');
        }

        return implode(' ', $messages);
    }
}
