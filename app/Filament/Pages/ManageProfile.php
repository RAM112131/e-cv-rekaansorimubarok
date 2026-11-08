<?php

namespace App\Filament\Pages;

use App\Models\SocialLink;
use App\Services\PublicContentCache;
use BackedEnum;
use Filament\Forms\Components\Repeater;
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

class ManageProfile extends Page implements HasSchemas
{
    use InteractsWithSchemas;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Profil Landing Page';

    protected static UnitEnum|string|null $navigationGroup = 'Manajemen Konten';

    protected static ?string $title = 'Kelola Profil Landing Page';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'headline' => $user->headline,
            'bio' => $user->bio,
            'phone' => $user->phone ?? '+62 882118269212',
            'location' => $user->location ?? 'Ciamis, Jawa Barat',
            'avatar' => $user->avatar,
            'social_links' => $user->socialLinks()
                ->get(['platform', 'url'])
                ->toArray(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama Hero & Landing Page')
                    ->description('Pengaturan nama, headline hero, dan bio yang akan tampil langsung di website portofolio utama.')
                    ->components([

                        TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('headline')
                            ->label('Headline Hero (Landing Page)')
                            ->maxLength(255)
                            ->placeholder('mis. Fullstack Web Developer & Student'),

                        Textarea::make('bio')
                            ->label('Bio / Perkenalan Singkat (Hero Section)')
                            ->rows(4)
                            ->maxLength(1000),
                    ])
                    ->columns(2),

                Section::make('Social Links (Tampil di Website Landing)')
                    ->components([
                        Repeater::make('social_links')
                            ->label('Daftar Akun Social Media')
                            ->schema([
                                Select::make('platform')
                                    ->options([
                                        'github' => 'GitHub',
                                        'linkedin' => 'LinkedIn',
                                        'instagram' => 'Instagram',
                                        'twitter' => 'X / Twitter',
                                        'youtube' => 'YouTube',
                                        'tiktok' => 'TikTok',
                                        'whatsapp' => 'WhatsApp',
                                        'telegram' => 'Telegram',
                                        'behance' => 'Behance',
                                        'facebook' => 'Facebook',
                                        'dribbble' => 'Dribbble',
                                        'medium' => 'Medium',
                                        'discord' => 'Discord',
                                        'website' => 'Website / Link Lainnya',
                                    ])
                                    ->searchable()
                                    ->required(),
                                TextInput::make('url')
                                    ->label('URL Akun / Profil')
                                    ->url()
                                    ->required(),
                            ])
                            ->columns(2)
                            ->reorderable()
                            ->addActionLabel('Tambah Social Link')
                            ->defaultItems(0),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $user = auth()->user();
        $user->update([
            'name' => $state['name'],
            'headline' => $state['headline'],
            'bio' => $state['bio'],
            'phone' => $state['phone'] ?? null,
            'location' => $state['location'] ?? null,
            'avatar' => $state['avatar'] ?? $user->avatar,
        ]);

        $user->socialLinks()->delete();

        $timestamp = now();
        $socialLinks = [];

        foreach (array_values($state['social_links'] ?? []) as $order => $link) {
            $socialLinks[] = [
                'user_id' => $user->id,
                'platform' => $link['platform'],
                'url' => $link['url'],
                'order' => $order,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        if ($socialLinks !== []) {
            SocialLink::query()->insert($socialLinks);
        }

        PublicContentCache::clear();

        Notification::make()
            ->title('Profil Landing Page berhasil diperbarui')
            ->success()
            ->send();
    }
}
