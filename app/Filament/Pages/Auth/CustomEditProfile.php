<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;

class CustomEditProfile extends BaseEditProfile
{
    public function getHeading(): string
    {
        return 'Pengaturan Akun & Keamanan';
    }

    public function getSubheading(): ?string
    {
        return 'Kelola informasi profil, kata sandi, dan autentikasi dua faktor akun admin Anda.';
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label('Nama Lengkap')
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label('Alamat Email')
            ->email()
            ->required()
            ->maxLength(255)
            ->unique(ignoreRecord: true);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label('Kata Sandi Baru')
            ->password()
            ->revealable()
            ->rule(\Illuminate\Validation\Rules\Password::default())
            ->autocomplete('new-password')
            ->dehydrated(fn ($state): bool => filled($state));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label('Konfirmasi Kata Sandi Baru')
            ->password()
            ->revealable()
            ->requiredWith('password')
            ->dehydrated(false);
    }

    protected function getSaveFormAction(): \Filament\Actions\Action
    {
        return parent::getSaveFormAction()
            ->label('Simpan Perubahan');
    }

    protected function getCancelFormAction(): \Filament\Actions\Action
    {
        return parent::getCancelFormAction()
            ->label('Batal');
    }
}
