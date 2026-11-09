<?php

namespace Database\Seeders;

use App\Models\Certificate;
use App\Models\CvEntry;
use App\Models\CvLanguage;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SocialLink;
use App\Models\User;
use App\Services\PublicContentCache;
use Illuminate\Database\Seeder;
use RuntimeException;

class DashboardSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::query()->where('role', 'admin')->first();

        if ($admin === null) {
            throw new RuntimeException('Run AdminUserSeeder with valid admin credentials before seeding portfolio content.');
        }

        $profile = $admin->portfolioProfile()->firstOrCreate([], [
            'name' => 'Reka Ansori Mubarok',
            'email' => 'reka114141@gmail.com',
            'phone' => '+62 882118269212',
            'location' => 'Ciamis, Jawa Barat',
            'headline' => 'Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya',
            'bio' => 'Mahasiswa aktif Sistem Informasi di UBSI yang antusias dalam pengembangan web. Memiliki pengalaman membangun aplikasi berbasis Laravel dengan integrasi pembayaran digital. Berkomitmen untuk terus belajar dan berkontribusi di lingkungan kerja profesional.',
            'cv_subtitle' => 'Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya',
            'cv_about' => 'Mahasiswa aktif Sistem Informasi di UBSI yang antusias dalam pengembangan web. Memiliki pengalaman membangun aplikasi berbasis Laravel dengan integrasi pembayaran digital. Berkomitmen untuk terus belajar dan berkontribusi di lingkungan kerja profesional.',
        ]);

        SocialLink::query()->firstOrCreate(
            ['portfolio_profile_id' => $profile->id, 'platform' => 'LinkedIn'],
            ['url' => 'https://www.linkedin.com/in/reka-ansori-mubarok-a5508427a', 'sort_order' => 1],
        );

        foreach ([
            [
                'institution' => 'Universitas Bina Sarana Informatika (UBSI) Kota Tasikmalaya',
                'degree' => 'S1',
                'major' => 'Sistem Informasi',
                'start_year' => 2024,
                'end_year' => null,
                'is_current' => true,
                'order' => 1,
            ],
            [
                'institution' => 'MAN 5 Ciamis',
                'degree' => null,
                'major' => 'MIPA',
                'start_year' => 2020,
                'end_year' => 2023,
                'is_current' => false,
                'order' => 2,
            ],
        ] as $education) {
            Education::query()->firstOrCreate(
                ['portfolio_profile_id' => $profile->id, 'institution' => $education['institution']],
                $education,
            );
        }

        foreach ([
            ['name' => 'Indonesia', 'rating' => 5, 'order' => 1],
            ['name' => 'Inggris', 'rating' => 3, 'order' => 2],
        ] as $language) {
            CvLanguage::query()->firstOrCreate(
                ['portfolio_profile_id' => $profile->id, 'name' => $language['name']],
                $language,
            );
        }

        foreach ([
            [
                'title' => 'Sistem Informasi Bersih Bumi (Manajemen Pengelolaan Sampah)',
                'slug' => 'sistem-informasi-bersih-bumi',
                'description' => "Membangun platform manajemen iuran pengangkutan sampah berbasis web untuk membantu RT/RW mengelola data warga dan pembayaran secara digital.\nMengintegrasikan Midtrans Payment Gateway (QRIS & Virtual Account) untuk transaksi non-tunai.\nMenerapkan autentikasi multi-role (Admin, Petugas, Warga) menggunakan Laravel Jetstream.",
                'year' => 2026,
                'role' => 'Tugas Kuliah',
                'tech_stack' => ['Laravel Jetstream', 'Midtrans'],
                'order' => 1,
                'is_featured' => true,
            ],
            [
                'title' => 'Website Smart Tourism Guide Kota Banjar',
                'slug' => 'website-smart-tourism-guide-kota-banjar',
                'description' => 'Mengembangkan web pemandu wisata online berbasis QR Code untuk meningkatkan aksesibilitas informasi wisata dan menyediakan pemesanan tiket secara online.',
                'year' => 2025,
                'role' => 'Tugas Kuliah',
                'tech_stack' => null,
                'order' => 2,
                'is_featured' => true,
            ],
            [
                'title' => 'Sistem Informasi Pemilihan OSIS',
                'slug' => 'sistem-informasi-pemilihan-osis',
                'description' => 'Membangun website pemilihan OSIS responsif dengan fitur pemilihan online dan manajemen kandidat.',
                'year' => 2025,
                'role' => 'Proyek Mandiri',
                'tech_stack' => null,
                'order' => 3,
                'is_featured' => false,
            ],
        ] as $projectData) {
            $project = Project::query()->firstOrCreate(
                ['portfolio_profile_id' => $profile->id, 'slug' => $projectData['slug']],
                [
                    'title' => $projectData['title'],
                    'description' => $projectData['description'],
                    'category' => 'Web Application',
                    'tech_stack' => $projectData['tech_stack'],
                    'year' => $projectData['year'],
                    'role' => $projectData['role'],
                    'is_featured' => $projectData['is_featured'],
                    'is_published' => true,
                    'order' => $projectData['order'],
                ],
            );

            CvEntry::query()->firstOrCreate(
                [
                    'portfolio_profile_id' => $profile->id,
                    'entry_type' => 'project',
                    'project_id' => $project->id,
                ],
                [
                    'title' => $projectData['title'],
                    'period' => (string) $projectData['year'],
                    'subtitle' => $projectData['role'],
                    'description' => $projectData['description'],
                    'order' => $projectData['order'],
                ],
            );
        }

        $experience = Experience::query()->firstOrCreate(
            [
                'portfolio_profile_id' => $profile->id,
                'title' => 'Staf Gudang',
                'company' => 'Anyar CCTV Bandung',
            ],
            [
                'location' => 'Bandung',
                'type' => 'Pengalaman Kerja',
                'start_date' => '2023-09-01',
                'end_date' => '2024-07-31',
                'is_current' => false,
                'description' => 'Melakukan inventarisasi barang, pencatatan stok masuk dan keluar, pengelolaan persediaan, serta pengaturan tata letak penyimpanan. Berkoordinasi dengan tim operasional, membantu proses pengiriman, dan menyusun laporan bulanan.',
                'order' => 1,
            ],
        );

        CvEntry::query()->firstOrCreate(
            [
                'portfolio_profile_id' => $profile->id,
                'entry_type' => 'experience',
                'experience_id' => $experience->id,
            ],
            [
                'title' => 'Staf Gudang',
                'period' => 'September 2023 - Juli 2024',
                'subtitle' => 'Anyar CCTV Bandung',
                'description' => $experience->description,
                'order' => 1,
            ],
        );

        foreach ([
            [
                'title' => 'Divisi HUMAS – Himpunan Mahasiswa Sistem Informasi',
                'period' => 'Januari - Desember 2025',
                'subtitle' => 'Pengurus · UBSI Kota Tasikmalaya',
                'description' => 'Bertugas membangun dan menjaga citra organisasi melalui komunikasi eksternal dan internal, serta mengelola hubungan dengan media dan platform digital.',
                'order' => 1,
            ],
            [
                'title' => 'Panitia Bakti Sosial Kemasyarakatan',
                'period' => '4-24 Agustus 2025',
                'subtitle' => 'Divisi Konsumsi · KMC Galuh Taruna Bandung',
                'description' => 'Bertanggung jawab atas pengelolaan konsumsi, termasuk perencanaan menu, koordinasi katering, dan distribusi makanan untuk panitia dan peserta kegiatan.',
                'order' => 2,
            ],
        ] as $organization) {
            CvEntry::query()->firstOrCreate(
                [
                    'portfolio_profile_id' => $profile->id,
                    'entry_type' => 'organization',
                    'title' => $organization['title'],
                ],
                $organization,
            );
        }

        foreach ([
            ['title' => 'Belajar Dasar Pemrograman Web', 'issuer' => 'Dicoding', 'year' => 2023, 'order' => 1],
            ['title' => 'Database Design & SQL', 'issuer' => 'MySkill', 'year' => 2023, 'order' => 2],
            ['title' => 'Laravel Framework', 'issuer' => 'BuildWith Angga', 'year' => 2024, 'order' => 3],
            ['title' => 'Google IT Support', 'issuer' => 'Google / Coursera', 'year' => 2024, 'order' => 4],
        ] as $certificate) {
            Certificate::query()->firstOrCreate(
                ['portfolio_profile_id' => $profile->id, 'title' => $certificate['title']],
                [
                    'issuer' => $certificate['issuer'],
                    'issue_date' => "{$certificate['year']}-01-01",
                    'order' => $certificate['order'],
                ],
            );
        }

        PublicContentCache::clear();
    }
}
