<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Models\Experience;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-user-group';

    protected static UnitEnum|string|null $navigationGroup = 'Portofolio';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Detail Pengalaman')
                    ->components([
                        TextInput::make('title')
                            ->label('Posisi / Jabatan / Peran')
                            ->placeholder('misal: Web Developer, Peserta Magang, Ketua Himpunan')
                            ->required(),

                        TextInput::make('company')
                            ->label('Perusahaan / Instansi / Organisasi')
                            ->placeholder('misal: PT Telkom, HIMA Informatika, SMK Negeri 1')
                            ->required(),

                        FileUpload::make('company_logo')
                            ->label('Logo Perusahaan / Organisasi')
                            ->image()
                            ->directory('experiences/logos')
                            ->imagePreviewHeight('60px')
                            ->columnSpanFull()
                            ->helperText('Unggah logo resmi (opsional).'),

                        Select::make('type')
                            ->label('Kategori & Tipe Pengalaman')
                            ->options([
                                'Pekerjaan Full-Time' => '💼 Pekerjaan Full-Time',
                                'Pekerjaan Part-Time' => '⏱️ Pekerjaan Part-Time',
                                'Freelance / Kontrak' => '💻 Freelance / Kontrak',
                                'Magang / Internship' => '🎓 Magang / Internship',
                                'PKL (Praktik Kerja Lapangan)' => '🏫 PKL (Praktik Kerja Lapangan)',
                                'Organisasi & Komunitas' => '👥 Organisasi & Komunitas',
                                'Sukarelawan / Volunteer' => '🤝 Sukarelawan / Volunteer',
                            ])
                            ->searchable()
                            ->required(),

                        TextInput::make('location')
                            ->label('Lokasi (Opsional)')
                            ->placeholder('misal: Jakarta, Indonesia atau Remote'),

                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),

                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->placeholder('Kosongkan jika masih aktif (Present)')
                            ->hidden(fn ($get) => $get('is_current')),

                        Toggle::make('is_current')
                            ->label('Masih Aktif / Bekerja Sampai Sekarang')
                            ->live()
                            ->default(false),

                        RichEditor::make('description')
                            ->label('Deskripsi Tanggung Jawab & Pencapaian')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'bulletList',
                                'orderedList',
                                'redo',
                                'undo',
                            ])
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->placeholder('Kosongkan untuk urutan terakhir'),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Dokumentasi Kegiatan / Pengalaman')
                    ->components([
                        FileUpload::make('documentation_photos')
                            ->label('Upload Foto Dokumentasi (Bisa Banyak / Slide)')
                            ->image()
                            ->multiple()
                            ->maxParallelUploads(4)
                            ->reorderable()
                            ->panelLayout('grid')
                            ->imagePreviewHeight('120px')
                            ->directory('experiences/documentation')
                            ->helperText('Unggah satu atau beberapa foto dokumentasi kegiatan saat bekerja, magang, PKL, atau berorganisasi.'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('company_logo')
                    ->label('Dokumentasi')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Posisi / Peran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('company')
                    ->label('Perusahaan / Organismi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pekerjaan Full-Time' => 'success',
                        'Pekerjaan Part-Time' => 'info',
                        'Freelance / Kontrak' => 'warning',
                        'Magang / Internship' => 'primary',
                        'PKL (Praktik Kerja Lapangan)' => 'primary',
                        'Organisasi & Komunitas' => 'purple',
                        'Sukarelawan / Volunteer' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Mulai')
                    ->date('M Y'),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Selesai')
                    ->formatStateUsing(function ($state, Experience $record) {
                        if ($record->is_current || ! $state) {
                            return 'Present (Sekarang)';
                        }

                        return $state->format('M Y');
                    }),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->actions([
                EditAction::make()
                    ->modalWidth('5xl'),
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
            'index' => Pages\ManageExperiences::route('/'),
        ];
    }
}
