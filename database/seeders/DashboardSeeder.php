<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use RuntimeException;

class DashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->first();

        if (! $admin) {
            $adminName = config('auth.seed_admin.name');
            $adminEmail = config('auth.seed_admin.email');
            $adminPassword = config('auth.seed_admin.password');

            if (
                ! is_string($adminEmail)
                || ! filter_var($adminEmail, FILTER_VALIDATE_EMAIL)
                || ! is_string($adminPassword)
                || strlen($adminPassword) < 12
            ) {
                throw new RuntimeException(
                    'Set a valid SEED_ADMIN_EMAIL and a SEED_ADMIN_PASSWORD with at least 12 characters before seeding when no admin user exists.'
                );
            }

            $admin = User::create([
                'name' => is_string($adminName) && $adminName !== '' ? $adminName : 'Portfolio Admin',
                'email' => $adminEmail,
                'password' => $adminPassword,
                'role' => 'admin',
            ]);
        }

        // 1. Seed Visitors (log kunjungan 7 hari terakhir)
        if (Visitor::count() === 0) {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $visitorCount = rand(8, 25);

                for ($j = 0; $j < $visitorCount; $j++) {
                    Visitor::create([
                        'ip_address' => '127.0.0.'.rand(1, 100),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0',
                        'page_url' => '/',
                        'referrer' => rand(0, 1) ? 'https://google.com' : 'https://github.com',
                        'session_id' => 'sess_'.bin2hex(random_bytes(8)),
                        'visited_at' => $date->copy()->addMinutes(rand(10, 1300)),
                    ]);
                }
            }
        }

        // 2. Seed Messages (pesan kontak)
        if (Message::count() === 0) {
            Message::create([
                'name' => 'Budi Santoso',
                'email' => 'budi@techcorp.id',
                'subject' => 'Penawaran Proyek Fullstack Web',
                'message' => 'Halo Ram, kami sedang mencari lead developer untuk pengerjaan platform SaaS. Bisakah kita berdiskusi lebih lanjut?',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(2),
            ]);

            Message::create([
                'name' => 'Siti Rahma',
                'email' => 'siti.hr@innovate.com',
                'subject' => 'Undangan Interview Senior Laravel Developer',
                'message' => 'Salam, kami tertarik dengan portofolio Anda dan ingin mengundang Anda dalam proses wawancara.',
                'is_read' => false,
                'created_at' => Carbon::now()->subHours(5),
            ]);

            Message::create([
                'name' => 'Alex Johnson',
                'email' => 'alex@globaldigital.com',
                'subject' => 'Konsultasi Arsitektur Web 3D',
                'message' => 'Sangat mengagumkan hero 3D di website portofolio Anda. Apakah Anda menerima jasa konsultasi freelance?',
                'is_read' => true,
                'replied_at' => Carbon::now()->subDay(),
                'created_at' => Carbon::now()->subDays(2),
            ]);
        }

        // 3. Seed Projects
        if (Project::count() === 0) {
            Project::create([
                'user_id' => $admin->id,
                'title' => 'E-Commerce Platform Laravel 13',
                'slug' => 'e-commerce-platform-laravel-13',
                'description' => 'Platform toko online modern dengan integrasi payment gateway dan Filament v4 dashboard.',
                'category' => 'Web Application',
                'tech_stack' => 'Laravel, Filament, Livewire, Tailwind CSS',
                'project_url' => 'https://example.com/demo-shop',
                'repo_url' => 'https://github.com/example/demo-shop',
                'is_featured' => true,
                'order' => 1,
            ]);

            Project::create([
                'user_id' => $admin->id,
                'title' => 'Interactive 3D Portfolio Website',
                'slug' => 'interactive-3d-portfolio-website',
                'description' => 'Website portofolio interaktif dengan Three.js & GSAP animations.',
                'category' => 'Web Design & 3D',
                'tech_stack' => 'Laravel 13, Three.js, GSAP, Tailwind v4',
                'project_url' => 'https://example.com/portfolio',
                'repo_url' => 'https://github.com/example/portfolio',
                'is_featured' => true,
                'order' => 2,
            ]);
        }

        // 4. Seed Skills
        if (Skill::count() === 0) {
            Skill::create(['user_id' => $admin->id, 'name' => 'Laravel', 'category' => 'Backend', 'level' => 95, 'order' => 1]);
            Skill::create(['user_id' => $admin->id, 'name' => 'PHP', 'category' => 'Backend', 'level' => 90, 'order' => 2]);
            Skill::create(['user_id' => $admin->id, 'name' => 'Filament PHP', 'category' => 'Admin Panel', 'level' => 90, 'order' => 3]);
            Skill::create(['user_id' => $admin->id, 'name' => 'Tailwind CSS', 'category' => 'Frontend', 'level' => 88, 'order' => 4]);
            Skill::create(['user_id' => $admin->id, 'name' => 'Three.js / GSAP', 'category' => 'Frontend 3D', 'level' => 80, 'order' => 5]);
        }

        // 5. Seed Experiences
        if (Experience::count() === 0) {
            Experience::create([
                'user_id' => $admin->id,
                'title' => 'Senior Fullstack Web Developer',
                'company' => 'Tech Synergy Solutions',
                'type' => 'Full-time',
                'start_date' => '2023-01-01',
                'description' => 'Mengembangkan aplikasi enterprise berbasis Laravel & Livewire dengan skalabilitas tinggi.',
                'order' => 1,
            ]);
        }

        // 6. Seed Certificates
        if (Certificate::count() === 0) {
            Certificate::create([
                'user_id' => $admin->id,
                'title' => 'Laravel Certified Developer',
                'issuer' => 'Laravel LLC',
                'issue_date' => '2024-05-15',
                'credential_url' => 'https://laravel.com/certification/verify/example',
                'order' => 1,
            ]);
        }
    }
}
