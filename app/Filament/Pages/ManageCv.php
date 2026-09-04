<?php

namespace App\Filament\Pages;

use App\Models\Education;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;

use UnitEnum;

class ManageCv extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Manajemen CV';

    protected static UnitEnum|string|null $navigationGroup = 'Manajemen Konten';

    protected static ?string $title = 'Manajemen Konten CV';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.pages.manage-cv';

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();
        $cvData = $user->cv_data ?? [];

        $defaultLanguages = [
            ['name' => 'Indonesia', 'rating' => 5],
            ['name' => 'Inggris', 'rating' => 3],
        ];

        $defaultCertifications = [
            ['name' => 'Belajar Dasar Pemrograman Web', 'issuer' => 'Dicoding', 'year' => '2023'],
            ['name' => 'Database Design & SQL', 'issuer' => 'MySkill', 'year' => '2023'],
            ['name' => 'Laravel Framework', 'issuer' => 'BuildWith Angga', 'year' => '2024'],
            ['name' => 'Google IT Support', 'issuer' => 'Google / Coursera', 'year' => '2024'],
        ];

        $defaultEducations = [
            [
                'institution' => 'Universitas Bina Sarana Informatika (UBSI) Kota Tasikmalaya',
                'degree' => 'S1',
                'major' => 'Sistem Informasi',
                'start_year' => '2024',
                'end_year' => 'Sekarang',
                'semester' => '4',
                'gpa' => '',
            ],
            [
                'institution' => 'MAN 5 Ciamis',
                'degree' => '',
                'major' => 'MIPA',
                'start_year' => '2020',
                'end_year' => '2023',
                'semester' => '',
                'gpa' => '',
            ],
        ];

        $defaultProjects = [
            [
                'name' => 'Sistem Informasi Bersih Bumi (Manajemen Pengelolaan Sampah)',
                'badge' => '2026',
                'sub' => 'Tugas Kuliah',
                'desc' => "• Membangun platform manajemen iuran pengangkutan sampah berbasis web untuk membantu RT/RW mengelola data warga dan pembayaran secara digital.\n• Mengintegrasikan Midtrans Payment Gateway (QRIS & Virtual Account) untuk kemudahan transaksi non-tunai.\n• Menerapkan autentikasi multi-role (Admin, Petugas, Warga) menggunakan Laravel Jetstream.",
            ],
            [
                'name' => 'Website Smart Tourism Guide Kota Banjar',
                'badge' => '2025',
                'sub' => 'Tugas Kuliah',
                'desc' => "• Mengembangkan Web Pemandu Wisata Online Berbasis QR Code untuk meningkatkan aksesibilitas informasi wisata.\n• Menyediakan sistem pemesanan tiket secara online",
            ],
            [
                'name' => 'Sistem Informasi Pemilihan OSIS',
                'badge' => '2025',
                'sub' => 'Proyek Mandiri',
                'desc' => 'Membangun website pemilihan OSIS responsif dengan fitur pemilihan online dan manajemen kandidat.',
            ],
            [
                'name' => 'Mengikuti Kegiatan Webinar "Membuat Sistem Task Manager Team dengan Laravel"',
                'badge' => '2025',
                'sub' => 'Webinar',
                'desc' => 'Mengikuti webinar yang diselenggarakan oleh ID-Networkers. Mempelajari dasar-dasar pengembangan sistem manajemen tugas tim menggunakan framework Laravel, termasuk struktur proyek, manajemen database, autentikasi, dan implementasi alur kerja kolaboratif.',
            ],
            [
                'name' => 'Staf Gudang di Anyar CCTV Bandung',
                'badge' => 'September 2023 - Juli 2024',
                'sub' => 'Pengalaman Kerja',
                'desc' => 'Melakukan tugas-tugas administrasi gudang secara menyeluruh, termasuk inventarisasi barang, pencatatan masuk-keluar stok, pengelolaan persediaan, dan pengaturan tata letak penyimpanan agar efisien. Berkoordinasi dengan tim operasional, memastikan kelengkapan barang, membantu proses pengiriman, dan menyusun laporan bulanan untuk mendukung kelancaran aktivitas gudang.',
            ],
        ];

        $defaultOrgs = [
            [
                'name' => 'Divisi HUMAS – Himpunan Mahasiswa Sistem Informasi',
                'badge' => 'Januari - Desember 2025',
                'sub' => 'Pengurus · UBSI Kota Tasikmalaya',
                'desc' => 'Bertugas membangun dan menjaga citra organisasi melalui komunikasi eksternal dan internal, mengelola hubungan dengan media dan platform digital.',
            ],
            [
                'name' => 'Panitia Bakti Sosial Kemasyarakatan',
                'badge' => '4-24 Agustus 2025',
                'sub' => 'Divisi Konsumsi · KMC Galuh Taruna Bandung',
                'desc' => 'Bertanggung jawab atas pengelolaan konsumsi, termasuk perencanaan menu, koordinasi katering, dan distribusi makanan untuk seluruh panitia atau dalam kegiatan.',
            ],
        ];

        $existingEducations = $user->educations()->get(['institution', 'degree', 'major', 'start_year', 'end_year', 'semester', 'gpa'])->toArray();

        $this->form->fill([
            'cv_avatar' => $user->cv_avatar,
            'cv_name' => $cvData['cv_name'] ?? $user->name,
            'cv_subtitle' => $cvData['cv_subtitle'] ?? 'Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya',
            'phone' => $user->phone ?? '+62 882118269212',
            'email' => $user->email ?? 'reka114141@gmail.com',
            'linkedin_url' => $user->linkedin_url ?? 'https://www.linkedin.com/in/reka-ansori-mubarok-a5508427a',
            'location' => $user->location ?? 'Ciamis, Jawa Barat',
            'cv_about' => $user->cv_about ?? 'Mahasiswa aktif Sistem Informasi di UBSI yang antusias dalam pengembangan web. Memiliki pengalaman membangun aplikasi berbasis Laravel dengan integrasi pembayaran digital. Berkomitmen untuk terus belajar dan berkontribusi di lingkungan kerja profesional.',
            'cv_languages' => $cvData['cv_languages'] ?? $defaultLanguages,
            'cv_certifications' => $cvData['cv_certifications'] ?? $defaultCertifications,
            'educations' => count($existingEducations) > 0 ? $existingEducations : $defaultEducations,
            'cv_projects' => $cvData['cv_projects'] ?? $defaultProjects,
            'cv_orgs' => $cvData['cv_orgs'] ?? $defaultOrgs,
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('preview_cv')
                ->label('Preview & Cetak CV (A4)')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('amber')
                ->url(url('/cv'))
                ->openUrlInNewTab(),
        ];
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('1. Header & Kontak CV')
                    ->description('Informasi utama dan foto profil resmi yang tampil pada bagian atas biru dokumen CV.')
                    ->components([
                        FileUpload::make('cv_avatar')
                            ->label('Foto Profil Resmi CV (Formal)')
                            ->image()
                            ->disk('public')
                            ->directory('cv_avatars')
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpan('full')
                            ->helperText('Unggah foto profil yang akan tampil di bagian header CV.'),
                        TextInput::make('cv_name')
                            ->label('Nama Lengkap di CV')
                            ->required()
                            ->helperText('Nama yang akan tampil di bagian header dokumen CV.'),
                        TextInput::make('cv_subtitle')
                            ->label('Subtitle / Tagline CV')
                            ->required()
                            ->placeholder('Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya')
                            ->helperText('Contoh: Mahasiswa Sistem Informasi | UBSI Kota Tasikmalaya'),
                        TextInput::make('phone')
                            ->label('Nomor Telepon')
                            ->placeholder('+62 882118269212')
                            ->helperText('Gunakan format internasional agar mudah dihubungi.'),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->placeholder('reka114141@gmail.com')
                            ->helperText('Email utama yang akan ditampilkan di CV.'),
                        TextInput::make('linkedin_url')
                            ->label('URL LinkedIn')
                            ->url()
                            ->placeholder('https://www.linkedin.com/in/reka-ansori-mubarok-a5508427a')
                            ->helperText('Pastikan URL LinkedIn aktif dan lengkap.'),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->placeholder('Ciamis, Jawa Barat')
                            ->helperText('Kota atau wilayah tempat Anda berada.'),
                    ])
                    ->columns(2),

                Section::make('2. Tentang Saya (Sidebar Left)')
                    ->components([
                        Textarea::make('cv_about')
                            ->label('Deskripsi Ringkas "Tentang Saya"')
                            ->rows(4)
                            ->required()
                            ->helperText('Ringkas dan fokus pada nilai utama yang ingin Anda tampilkan untuk perekrut.'),
                    ]),

                Section::make('3. Keahlian Teknis (Skill Bars Left)')
                    ->description('Daftar keahlian teknis otomatis diambil langsung dari data master Skills.')
                    ->components([
                        Placeholder::make('skills_master_info')
                            ->label('Informasi Master Skills')
                            ->content('Data keahlian teknis diambil otomatis dari menu master Skills (/admin/skills). Gunakan menu Skills untuk mengubah daftar dan kategori teknis Anda.'),
                    ]),

                Section::make('4. Keahlian Bahasa (Opsional Sidebar Left)')
                    ->description('Kosongkan item jika tidak ingin menampilkan bagian Bahasa pada dokumen CV.')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        Repeater::make('cv_languages')
                            ->label('Daftar Keahlian Bahasa')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Bahasa')
                                    ->required()
                                    ->placeholder('Indonesia / Inggris / Jepang'),
                                Select::make('rating')
                                    ->label('Tingkat Kemahiran')
                                    ->options([
                                        1 => '1 Dot - Dasar',
                                        2 => '2 Dots - Elemen',
                                        3 => '3 Dots - Menengah',
                                        4 => '4 Dots - Mahir',
                                        5 => '5 Dots - Fasih / Penutur Asli',
                                    ])
                                    ->default(5)
                                    ->required(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Tambah Bahasa')
                            ->defaultItems(0)
                            ->helperText('Gunakan bagian ini jika Anda ingin menampilkan bahasa di CV.'),
                    ]),

                Section::make('5. Sertifikasi & Ujikom (Opsional Sidebar Left)')
                    ->description('Daftar sertifikasi kompetensi, ujikom, atau sertifikat pelatihan khusus untuk CV.')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        Repeater::make('cv_certifications')
                            ->label('Daftar Sertifikasi / Ujikom')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Sertifikasi / Pelatihan / Ujikom')
                                    ->required()
                                    ->placeholder('Sertifikasi Pemrograman Web BNSP / Laravel Certified'),
                                TextInput::make('issuer')
                                    ->label('Lembaga Penerbit / Penyelenggara')
                                    ->required()
                                    ->placeholder('BNSP / Dicoding / BuildWith Angga'),
                                TextInput::make('year')
                                    ->label('Tahun')
                                    ->placeholder('2024'),
                            ])
                            ->columns(3)
                            ->reorderable()
                            ->addActionLabel('Tambah Sertifikasi')
                            ->defaultItems(0)
                            ->helperText('Masukkan satu sertifikasi per baris untuk kejelasan dokumen CV.'),
                    ]),

                Section::make('6. Riwayat Pendidikan (Timeline Main)')
                    ->components([
                        Repeater::make('educations')
                            ->label('Daftar Pendidikan')
                            ->schema([
                                TextInput::make('institution')
                                    ->label('Nama Kampus / Sekolah')
                                    ->required()
                                    ->columnSpan('full'),
                                TextInput::make('degree')
                                    ->label('Tingkat / Gelar')
                                    ->placeholder('S1 / SMA'),
                                TextInput::make('major')
                                    ->label('Jurusan')
                                    ->placeholder('Sistem Informasi / MIPA'),
                                TextInput::make('start_year')
                                    ->label('Tahun Mulai')
                                    ->placeholder('2024'),
                                TextInput::make('end_year')
                                    ->label('Tahun Selesai')
                                    ->placeholder('Sekarang / 2023'),
                                TextInput::make('semester')
                                    ->label('Semester')
                                    ->placeholder('4'),
                                TextInput::make('gpa')
                                    ->label('IPK')
                                    ->placeholder('3.75 / 4.00'),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Tambah Pendidikan')
                            ->helperText('Tambahkan riwayat pendidikan utama yang ingin ditonjolkan di CV.'),
                    ]),

                Section::make('7. Proyek & Pengalaman Kerja (Timeline Main)')
                    ->components([
                        Repeater::make('cv_projects')
                            ->label('Daftar Proyek / Pengalaman')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Judul Proyek / Pengalaman Kerja')
                                    ->required()
                                    ->columnSpan('full'),
                                TextInput::make('badge')
                                    ->label('Tahun / Periode')
                                    ->placeholder('2026 / Sep 2023 - Jul 2024'),
                                TextInput::make('sub')
                                    ->label('Subtitle / Peran')
                                    ->placeholder('Tugas Kuliah / Staf Gudang'),
                                Textarea::make('desc')
                                    ->label('Deskripsi / Poin Rincian')
                                    ->rows(4)
                                    ->helperText('Fleksibel: paragraf biasa atau daftar poin dengan tanda (•, -, *).')
                                    ->placeholder("Contoh Paragraf:\nMembangun website pemilihan OSIS responsif dengan fitur pemilihan online.\n\nContoh List:\n• Membangun platform web iuran sampah.\n• Mengintegrasikan Midtrans Payment Gateway."),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Tambah Proyek / Pengalaman')
                            ->helperText('Masukkan proyek dan pengalaman kerja yang paling relevan untuk CV Anda.'),
                    ]),

                Section::make('8. Organisasi & Kepanitiaan (Timeline Main)')
                    ->description('Tampilkan peran organisasi atau kepanitiaan yang memperkuat nilai kepemimpinan dan kolaborasi Anda.')
                    ->collapsible()
                    ->collapsed()
                    ->components([
                        Repeater::make('cv_orgs')
                            ->label('Daftar Organisasi / Panitia')
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Kegiatan / Divisi')
                                    ->required()
                                    ->columnSpan('full'),
                                TextInput::make('badge')
                                    ->label('Periode')
                                    ->placeholder('Januari - Desember 2025'),
                                TextInput::make('sub')
                                    ->label('Jabatan & Organisasi')
                                    ->placeholder('Pengurus · UBSI Kota Tasikmalaya'),
                                Textarea::make('desc')
                                    ->label('Deskripsi Peran & Tanggung Jawab')
                                    ->rows(4)
                                    ->helperText('Fleksibel: paragraf atau daftar poin untuk menggambarkan kontribusi Anda.' )
                                    ->placeholder("Contoh Paragraf:\nBertugas membangun dan menjaga citra organisasi melalui komunikasi eksternal.\n\nContoh List:\n• Mengelola komunikasi eksternal dan internal.\n• Mengatur distribusi konsumsi panitia."),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Tambah Organisasi')
                            ->helperText('Gunakan bagian ini untuk organisasi, kepanitiaan, atau peran non-formal yang relevan.'),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $user = auth()->user();
        $user->update([
            'name' => $state['cv_name'] ?? $user->name,
            'cv_avatar' => $state['cv_avatar'] ?? $user->cv_avatar,
            'cv_subtitle' => $state['cv_subtitle'] ?? null,
            'phone' => $state['phone'] ?? null,
            'email' => $state['email'] ?? $user->email,
            'linkedin_url' => $state['linkedin_url'] ?? null,
            'location' => $state['location'] ?? null,
            'cv_about' => $state['cv_about'] ?? null,
            'cv_data' => [
                'cv_name' => $state['cv_name'] ?? $user->name,
                'cv_languages' => $state['cv_languages'] ?? [],
                'cv_certifications' => $state['cv_certifications'] ?? [],
                'cv_projects' => $state['cv_projects'] ?? [],
                'cv_orgs' => $state['cv_orgs'] ?? [],
            ],
        ]);

        // Simpan educations
        $user->educations()->delete();
        foreach ($state['educations'] ?? [] as $index => $edu) {
            Education::create([
                'user_id' => $user->id,
                'institution' => $edu['institution'],
                'degree' => $edu['degree'] ?? null,
                'major' => $edu['major'] ?? null,
                'start_year' => $edu['start_year'] ?? null,
                'end_year' => $edu['end_year'] ?? null,
                'semester' => $edu['semester'] ?? null,
                'gpa' => $edu['gpa'] ?? null,
                'order' => $index,
            ]);
        }

        Notification::make()
            ->title('Seluruh Konten CV berhasil diperbarui')
            ->success()
            ->send();
    }
}
