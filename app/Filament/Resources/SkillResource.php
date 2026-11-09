<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SkillResource\Pages;
use App\Models\Skill;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class SkillResource extends Resource
{
    protected static ?string $model = Skill::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static UnitEnum|string|null $navigationGroup = 'Portofolio';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Skill')
                    ->components([
                        TextInput::make('name')
                            ->label('Nama Skill / Keahlian')
                            ->required(),

                        TextInput::make('category')
                            ->label('Kategori')
                            ->placeholder('misal: Backend, Frontend, DevOps, Database'),

                        TextInput::make('level')
                            ->label('Tingkat Keahlian (%)')
                            ->numeric()
                            ->default(80)
                            ->minValue(1)
                            ->maxValue(100)
                            ->required(),

                        TextInput::make('order')
                            ->label('Urutan Tampilan')
                            ->numeric()
                            ->placeholder('Kosongkan untuk urutan terakhir'),

                        Textarea::make('description')
                            ->label('Deskripsi Keahlian')
                            ->placeholder('Jelaskan pengalaman atau tingkat keahlian Anda di bidang ini...')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->columnSpan(1),

                Section::make('Logo & Icon Skill')
                    ->components([
                        FileUpload::make('icon')
                            ->label('Upload Logo / Icon Asli (PNG / SVG / WEBP)')
                            ->image()
                            ->disk('public')
                            ->directory('skills/icons')
                            ->imagePreviewHeight('140px')
                            ->helperText('Unggah gambar logo asli dari teknologi atau skill ini.'),
                    ])
                    ->columnSpan(1),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('icon')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Skill')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Kategori')
                    ->searchable()
                    ->badge(),

                Tables\Columns\TextColumn::make('level')
                    ->label('Tingkat (%)')
                    ->formatStateUsing(fn ($state) => "{$state}%")
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
            'index' => Pages\ManageSkills::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $profileId = auth()->user()?->portfolioProfile?->getKey();

        return parent::getEloquentQuery()->where('portfolio_profile_id', $profileId);
    }
}
