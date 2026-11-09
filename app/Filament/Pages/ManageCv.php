<?php

namespace App\Filament\Pages;

use App\Models\Certificate;
use App\Models\CvEntry;
use App\Models\CvLanguage;
use App\Models\Education;
use App\Models\SocialLink;
use App\Services\PublicContentCache;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;
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
        $profile = auth()->user()->portfolioProfile()->with([
            'educations',
            'socialLinks',
            'cvLanguages',
            'certificates' => fn ($query) => $query->where('show_on_cv', true)->orderBy('order'),
            'cvEntries',
        ])->firstOrFail();

        $entries = $profile->cvEntries->map(fn (CvEntry $entry): array => [
            'name' => $entry->title,
            'badge' => $entry->period,
            'sub' => $entry->subtitle,
            'desc' => $entry->description,
            'entry_type' => $entry->entry_type,
        ]);

        $this->form->fill([
            'cv_avatar' => $profile->cv_avatar,
            'cv_file' => $profile->cv_file,
            'cv_name' => $profile->name,
            'cv_subtitle' => $profile->cv_subtitle,
            'phone' => $profile->phone,
            'email' => $profile->email,
            'linkedin_url' => $profile->socialLinks
                ->first(fn (SocialLink $link): bool => strtolower($link->platform) === 'linkedin')
                ?->url,
            'location' => $profile->location,
            'cv_about' => $profile->cv_about,
            'cv_languages' => $profile->cvLanguages
                ->map(fn (CvLanguage $language): array => [
                    'name' => $language->name,
                    'rating' => $language->rating,
                ])
                ->all(),
            'cv_certifications' => $profile->certificates
                ->map(fn (Certificate $certificate): array => [
                    'id' => $certificate->id,
                    'name' => $certificate->title,
                    'issuer' => $certificate->issuer,
                    'year' => $certificate->issue_date?->format('Y'),
                ])
                ->all(),
            'educations' => $profile->educations
                ->map(fn (Education $education): array => [
                    'institution' => $education->institution,
                    'degree' => $education->degree,
                    'major' => $education->major,
                    'start_year' => $education->start_year,
                    'end_year' => $education->end_year,
                    'is_current' => $education->is_current,
                    'semester' => $education->semester,
                    'gpa' => $education->gpa,
                ])
                ->all(),
            'cv_projects' => $entries
                ->whereIn('entry_type', ['project', 'experience'])
                ->map(fn (array $entry): array => collect($entry)->except('entry_type')->all())
                ->values()
                ->all(),
            'cv_orgs' => $entries
                ->where('entry_type', 'organization')
                ->map(fn (array $entry): array => collect($entry)->except('entry_type')->all())
                ->values()
                ->all(),
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
                        FileUpload::make('cv_file')
                            ->label('File CV PDF untuk Diunduh')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240)
                            ->disk('public')
                            ->directory('cv')
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
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
                                Hidden::make('id'),
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
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 10)
                                    ->required()
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
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 10)
                                    ->placeholder('2024'),
                                TextInput::make('end_year')
                                    ->label('Tahun Selesai')
                                    ->numeric()
                                    ->minValue(1900)
                                    ->maxValue(now()->year + 10)
                                    ->placeholder('2027'),
                                Toggle::make('is_current')
                                    ->label('Masih berlangsung')
                                    ->live(),
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
                                    ->helperText('Fleksibel: paragraf atau daftar poin untuk menggambarkan kontribusi Anda.')
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
        $profile = auth()->user()->portfolioProfile()->firstOrFail();

        DB::transaction(function () use ($profile, $state): void {
            $profile->update([
                'name' => $state['cv_name'],
                'cv_avatar' => $state['cv_avatar'] ?? null,
                'cv_file' => $state['cv_file'] ?? null,
                'cv_subtitle' => $state['cv_subtitle'] ?? null,
                'phone' => $state['phone'] ?? null,
                'email' => $state['email'] ?? null,
                'location' => $state['location'] ?? null,
                'cv_about' => $state['cv_about'] ?? null,
            ]);

            $linkedin = $profile->socialLinks()
                ->where('platform', 'linkedin')
                ->first();

            if (blank($state['linkedin_url'] ?? null)) {
                $linkedin?->delete();
            } elseif ($linkedin !== null) {
                $linkedin->update(['url' => $state['linkedin_url']]);
            } else {
                $profile->socialLinks()->create([
                    'platform' => 'linkedin',
                    'url' => $state['linkedin_url'],
                    'sort_order' => (int) ($profile->socialLinks()->max('sort_order') ?? 0) + 1,
                ]);
            }

            $profile->educations()->delete();
            $profile->educations()->createMany(
                collect($state['educations'] ?? [])
                    ->values()
                    ->map(fn (array $education, int $index): array => [
                        'institution' => $education['institution'],
                        'degree' => $education['degree'] ?? null,
                        'major' => $education['major'] ?? null,
                        'start_year' => filled($education['start_year'] ?? null)
                            ? (int) $education['start_year']
                            : null,
                        'end_year' => ! empty($education['is_current']) || blank($education['end_year'] ?? null)
                            ? null
                            : (int) $education['end_year'],
                        'is_current' => (bool) ($education['is_current'] ?? false),
                        'semester' => $education['semester'] ?? null,
                        'gpa' => $education['gpa'] ?? null,
                        'order' => $index,
                    ])
                    ->all(),
            );

            $profile->cvLanguages()->delete();
            $profile->cvLanguages()->createMany(
                collect($state['cv_languages'] ?? [])
                    ->values()
                    ->map(fn (array $language, int $index): array => [
                        'name' => $language['name'],
                        'rating' => (int) $language['rating'],
                        'order' => $index,
                    ])
                    ->all(),
            );

            $profile->certificates()->where('show_on_cv', true)->update(['show_on_cv' => false]);

            foreach ($state['cv_certifications'] ?? [] as $index => $certificateData) {
                $year = (int) $certificateData['year'];
                $attributes = [
                    'title' => $certificateData['name'],
                    'issuer' => $certificateData['issuer'],
                    'issue_date' => sprintf('%04d-01-01', $year),
                    'show_on_cv' => true,
                    'order' => $index + 1,
                ];
                $certificateId = $certificateData['id'] ?? null;

                if ($certificateId !== null) {
                    $profile->certificates()->findOrFail($certificateId)->update($attributes);

                    continue;
                }

                $profile->certificates()->create($attributes + ['is_published' => true]);
            }

            $profile->cvEntries()->delete();
            $entries = collect($state['cv_projects'] ?? [])
                ->values()
                ->map(fn (array $entry, int $index): array => [
                    'entry_type' => 'project',
                    'title' => $entry['name'],
                    'period' => $entry['badge'] ?? null,
                    'subtitle' => $entry['sub'] ?? null,
                    'description' => $entry['desc'] ?? null,
                    'order' => $index,
                ])
                ->merge(
                    collect($state['cv_orgs'] ?? [])
                        ->values()
                        ->map(fn (array $entry, int $index): array => [
                            'entry_type' => 'organization',
                            'title' => $entry['name'],
                            'period' => $entry['badge'] ?? null,
                            'subtitle' => $entry['sub'] ?? null,
                            'description' => $entry['desc'] ?? null,
                            'order' => count($state['cv_projects'] ?? []) + $index,
                        ]),
                )
                ->all();

            $profile->cvEntries()->createMany($entries);
        });

        PublicContentCache::clear();

        Notification::make()
            ->title('Seluruh Konten CV berhasil diperbarui')
            ->success()
            ->send();
    }
}
