<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateResource\Pages;
use App\Models\Certificate;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class CertificateResource extends Resource
{
    protected static ?string $model = Certificate::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-check';

    protected static UnitEnum|string|null $navigationGroup = 'Portofolio';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Sertifikat')
                    ->components([
                        TextInput::make('title')
                            ->label('Judul Sertifikat / Kelulusan')
                            ->placeholder('misal: AWS Certified Solutions Architect, Laravel Web Development')
                            ->required(),

                        Select::make('certificate_category_id')
                            ->label('Kategori Sertifikat')
                            ->relationship('certificateCategory', 'name')
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required(),
                            ])
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Kategori Sertifikat'),


                        TextInput::make('issuer')
                            ->label('Lembaga / Instansi Penerbit')
                            ->placeholder('misal: Laravel LLC, Google, AWS, Dicoding')
                            ->required(),

                        TextInput::make('credential_id')
                            ->label('ID / No. Lisensi Sertifikat (Opsional)')
                            ->placeholder('misal: UC-12345678'),

                        DatePicker::make('issue_date')
                            ->label('Tanggal Mulai / Pelaksanaan')
                            ->helperText('Tanggal dimulainya kegiatan (misal: 12 Juli 2024)')
                            ->required(),

                        DatePicker::make('expiration_date')
                            ->label('Tanggal Selesai Kegiatan (Opsional)')
                            ->placeholder('Isi jika berlangsung beberapa hari')
                            ->helperText('Kosongkan jika kegiatan berlangsung 1 hari saja'),

                        TextInput::make('credential_url')
                            ->label('URL Verifikasi Online (Opsional)')
                            ->placeholder('https://coursera.org/verify/12345')
                            ->url()
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('Deskripsi Sertifikat (Opsional)')
                            ->placeholder('Penjelasan singkat mengenai materi, kompetensi, atau pencapaian sertifikat')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->placeholder('Kosongkan untuk urutan terakhir'),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Gambar / Dokumen Sertifikat')
                    ->components([
                        FileUpload::make('image')
                            ->label('Upload Gambar Sertifikat (PNG / JPG / WEBP)')
                            ->image()
                            ->disk('public')
                            ->directory('certificates/images')
                            ->imagePreviewHeight('140px')
                            ->helperText('Unggah foto/tangkapan layar dokumen sertifikat fisik.'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => $query->with('certificateCategory'))
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Foto')
                    ->square(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Judul Sertifikat')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('certificateCategory.name')
                    ->label('Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('warning')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('issuer')
                    ->label('Penerbit')
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('issue_date')
                    ->label('Tanggal Kegiatan')
                    ->formatStateUsing(function ($state, Certificate $record) {
                        if (! $state) {
                            return '-';
                        }
                        if ($record->expiration_date) {
                            return $state->format('d M Y').' s/d '.$record->expiration_date->format('d M Y');
                        }

                        return $state->format('d M Y');
                    })
                    ->sortable(),

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
            'index' => Pages\ManageCertificates::route('/'),
        ];
    }
}