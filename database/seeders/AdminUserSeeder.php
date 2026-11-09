<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    private const ADMIN_NAME = 'Portfolio Admin';

    private const ADMIN_EMAIL = 'admin@portofolio.test';

    private const ADMIN_PASSWORD = 'RekaAdmin#2026';

    public function run(): void
    {
        if (! app()->environment(['local', 'testing'])) {
            throw new RuntimeException('The development admin seeder may only run in local or testing environments.');
        }

        if ($this->command === null) {
            throw new RuntimeException(
                'Run this seeder with php artisan db:seed so the admin credentials are shown.'
            );
        }

        $admin = User::query()->where('email', self::ADMIN_EMAIL)->first();

        if ($admin === null && User::query()->where('role', 'admin')->exists()) {
            return;
        }

        if ($admin !== null && $admin->role !== 'admin') {
            throw new RuntimeException(
                'The default admin email already belongs to a non-admin user. Change ADMIN_EMAIL in AdminUserSeeder before seeding.'
            );
        }

        if ($admin === null) {
            $admin = User::withoutEvents(fn (): User => User::create([
                'name' => self::ADMIN_NAME,
                'email' => self::ADMIN_EMAIL,
                'password' => Hash::make(self::ADMIN_PASSWORD),
                'role' => 'admin',
            ]));
        } elseif (! Hash::check(self::ADMIN_PASSWORD, $admin->password)) {
            $admin->forceFill(['password' => Hash::make(self::ADMIN_PASSWORD)])->save();
        }

        $this->command->newLine();
        $this->command->info('Development admin credentials (local only):');
        $this->command->line('Email: '.self::ADMIN_EMAIL);
        $this->command->line('Password: '.self::ADMIN_PASSWORD);
    }
}
