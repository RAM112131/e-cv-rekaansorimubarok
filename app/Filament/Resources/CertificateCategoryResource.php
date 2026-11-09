<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CertificateCategoryResource\Pages;
use App\Models\CertificateCategory;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CertificateCategoryResource extends Resource
{
    protected static ?string $model = CertificateCategory::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-tag';

    protected static UnitEnum|string|null $navigationGroup = 'Portofolio';

    protected static ?string $navigationLabel = 'Kategori Sertifikat';

    protected static ?string $modelLabel = 'Kategori Sertifikat';

    protected static ?string $pluralModelLabel = 'Kategori Sertifikat';

    protected static bool $shouldRegisterNavigation = false;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Kategori Sertifikat')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Kategori')
                            ->placeholder('misal: Web Development, Mobile Development, Cloud & DevOps')
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->placeholder('Kosongkan untuk urutan terakhir')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Kategori')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('amber'),

                Tables\Columns\TextColumn::make('certificates_count')
                    ->counts('certificates')
                    ->label('Jumlah Sertifikat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->reorderable('order')
            ->actions([
                EditAction::make()
                    ->modalWidth('md'),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCertificateCategories::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $profileId = auth()->user()?->portfolioProfile?->getKey();

        return parent::getEloquentQuery()->where('portfolio_profile_id', $profileId);
    }
}
