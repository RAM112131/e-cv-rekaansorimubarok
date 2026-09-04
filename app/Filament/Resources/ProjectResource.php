<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProjectResource\Pages;
use App\Models\Project;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';

    protected static UnitEnum|string|null $navigationGroup = 'Portofolio';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Proyek')
                    ->components([
                        TextInput::make('title')
                            ->label('Judul Proyek')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($set, ?string $state) => $set('slug', Str::slug($state))),

                        TextInput::make('slug')
                            ->label('Slug URL')
                            ->required()
                            ->unique(Project::class, 'slug', ignoreRecord: true),

                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('misal: Web Application, Mobile App, 3D Design'),

                        TextInput::make('tech_stack')
                            ->label('Tech Stack')
                            ->placeholder('misal: Laravel, Livewire, Tailwind CSS, Three.js'),

                        Textarea::make('description')
                            ->label('Deskripsi Proyek')
                            ->rows(4)
                            ->columnSpanFull(),

                        TextInput::make('project_url')
                            ->label('Link Live Project / Demo')
                            ->url(),

                        TextInput::make('repo_url')
                            ->label('Link Repository GitHub')
                            ->url(),

                        Toggle::make('is_published')
                            ->label('Publish ke Website')
                            ->default(true)
                            ->helperText('Aktifkan untuk Publish ke website utama. Nonaktifkan jika hanya simpan sebagai Draft.'),

                        TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->placeholder('Kosongkan untuk urutan terakhir'),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Media & Gambar Proyek')
                    ->components([
                        FileUpload::make('image')
                            ->label('Gambar Cover / Thumbnail Utama')
                            ->image()
                            ->imagePreviewHeight('140px')
                            ->disk('public')
                            ->directory('projects/covers'),

                        FileUpload::make('gallery')
                            ->label('Galeri Screenshot Review (Grid / Slide)')
                            ->image()
                            ->multiple()
                            ->reorderable()
                            ->panelLayout('grid')
                            ->imagePreviewHeight('120px')
                            ->disk('public')
                            ->directory('projects/gallery'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Cover')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Proyek')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('tech_stack')
                    ->label('Tech Stack')
                    ->limit(30),

                Tables\Columns\ToggleColumn::make('is_published')
                    ->label('Publish / Draft'),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->actions([
                EditAction::make()
                    ->modalWidth('6xl'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProjects::route('/'),
        ];
    }
}