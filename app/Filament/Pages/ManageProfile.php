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
        $profile = auth()->user()->portfolioProfile()->with('socialLinks')->firstOrFail();

        $this->form->fill([
            'name' => $profile->name,
            'headline' => $profile->headline,
            'bio' => $profile->bio,
            'phone' => $profile->phone,
            'location' => $profile->location,
            'avatar' => $profile->avatar,
            'social_links' => $profile->socialLinks
                ->map(fn (SocialLink $socialLink): array => [
                    'platform' => $socialLink->platform,
                    'url' => $socialLink->url,
                ])
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

        $profile = auth()->user()->portfolioProfile()->firstOrFail();
        $profile->update([
            'name' => $state['name'],
            'headline' => $state['headline'],
            'bio' => $state['bio'],
            'phone' => $state['phone'] ?? null,
            'location' => $state['location'] ?? null,
            'avatar' => $state['avatar'] ?? $profile->avatar,
        ]);

        $profile->socialLinks()->delete();
        $profile->socialLinks()->createMany(
            collect($state['social_links'] ?? [])
                ->values()
                ->map(fn (array $link, int $index): array => [
                    'platform' => $link['platform'],
                    'url' => $link['url'],
                    'sort_order' => $index,
                ])
                ->all(),
        );

        PublicContentCache::clear();

        Notification::make()
            ->title('Profil Landing Page berhasil diperbarui')
            ->success()
            ->send();
    }
}
