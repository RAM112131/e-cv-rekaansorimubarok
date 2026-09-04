<?php

namespace App\Filament\Widgets;

use App\Models\Message;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentMessagesWidget extends BaseWidget
{
    protected static ?int $sort = 4;
    protected ?string $pollingInterval = null;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Pesan Kontak Terbaru';

    // Lazy dinonaktifkan karena di php artisan serve (single-thread), 
    // request concurrent AJAX malah bikin antrian panjang dan loading lama.
    protected static bool $isLazy = false;

    public function table(Table $table): Table
    {
        return $table
            ->query(Message::query()->latest()->limit(5))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Pengirim')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subject')
                    ->label('Subjek')
                    ->limit(35)
                    ->default('-'),

                Tables\Columns\IconColumn::make('is_read')
                    ->label('Dibaca')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-envelope')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->paginated(false);
    }
}