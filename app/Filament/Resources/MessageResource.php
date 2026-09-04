<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MessageResource\Pages;
use App\Mail\MessageReplyMail;
use App\Models\Message;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use UnitEnum;

class MessageResource extends Resource
{
    protected static ?string $model = Message::class;

    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-envelope';

    protected static UnitEnum|string|null $navigationGroup = 'Komunikasi';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        // Cache selama 60 detik agar tidak query DB di setiap page navigation
        $count = Cache::remember('nav.badge.unread_messages', 60, function () {
            return static::getModel()::where('is_read', false)->count();
        });

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name')
                        ->label('Nama Pengirim')
                        ->disabled(),

                    TextInput::make('email')
                        ->label('Email Pengirim')
                        ->disabled(),

                    TextInput::make('subject')
                        ->label('Subjek')
                        ->disabled(),

                    Toggle::make('is_read')
                        ->label('Sudah Dibaca')
                        ->default(true),

                    Textarea::make('message')
                        ->label('Isi Pesan')
                        ->rows(6)
                        ->disabled()
                        ->columnSpanFull(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
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

                Tables\Columns\TextColumn::make('replied_at')
                    ->label('Dibalas Pada')
                    ->dateTime('d M Y, H:i')
                    ->placeholder('Belum dibalas')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Action::make('view')
                    ->label('Lihat Pesan')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Detail Pesan Kontak')
                    ->fillForm(fn (Message $record): array => [
                        'name' => $record->name,
                        'email' => $record->email,
                        'subject' => $record->subject,
                        'message' => $record->message,
                    ])
                    ->form([
                        TextInput::make('name')->label('Nama Pengirim')->disabled(),
                        TextInput::make('email')->label('Email Pengirim')->disabled(),
                        TextInput::make('subject')->label('Subjek')->disabled(),
                        Textarea::make('message')->label('Isi Pesan')->rows(6)->disabled(),
                    ])
                    ->action(fn (Message $record) => $record->update(['is_read' => true]))
                    ->modalSubmitActionLabel('Tutup & Tandai Dibaca'),

                Action::make('reply')
                    ->label('Balas via Email')
                    ->icon('heroicon-o-envelope')
                    ->color('amber')
                    ->modalHeading(fn (Message $record) => "Balas Pesan Email ke: {$record->name}")
                    ->fillForm(fn (Message $record): array => [
                        'recipient_email' => $record->email,
                        'salutation' => "Yth. Bapak/Ibu {$record->name},",
                        'reply_content' => '',
                        'closing' => 'Hormat saya,',
                    ])
                    ->form([
                        TextInput::make('recipient_email')
                            ->label('Email Tujuan')
                            ->disabled(),
                        TextInput::make('salutation')
                            ->label('Salam Pembuka')
                            ->required()
                            ->placeholder('mis. Yth. Bapak/Ibu...'),
                        Textarea::make('reply_content')
                            ->label('Isi Pesan Balasan')
                            ->placeholder('Tuliskan balasan Anda di sini...')
                            ->rows(6)
                            ->required(),
                        TextInput::make('closing')
                            ->label('Salam Penutup')
                            ->required()
                            ->placeholder('mis. Hormat saya,'),
                    ])
                    ->action(function (Message $record, array $data): void {
                        $admin = auth()->user();
                        $adminName = $admin?->name ?? 'Ram';
                        $adminHeadline = $admin?->headline;

                        try {
                            Mail::to($record->email)->send(new MessageReplyMail(
                                messageRecord: $record,
                                salutation: $data['salutation'],
                                replyContent: $data['reply_content'],
                                closing: $data['closing'],
                                senderName: $adminName,
                                senderHeadline: $adminHeadline
                            ));
                        } catch (\Throwable $e) {
                            // Log silent jika server mail belum diset di .env
                        }

                        $record->update([
                            'is_read' => true,
                            'replied_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Balasan pesan berhasil dikirim ke ' . $record->email)
                            ->success()
                            ->send();
                    })
                    ->modalSubmitActionLabel('Kirim Balasan Email'),

                Action::make('reply_whatsapp')
                    ->label('Balas via WhatsApp')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->color('success')
                    ->visible(fn (Message $record): bool => (bool) preg_match('/(?:\+?62|0)[0-9\s\-]{8,14}/', $record->message))
                    ->modalHeading('Balas Pesan via WhatsApp')
                    ->modalDescription('Nomor WhatsApp terdeteksi dari isi pesan pengirim.')
                    ->modalWidth('md')
                    ->form([
                        TextInput::make('phone_number')
                            ->label('Nomor WhatsApp Pengirim')
                            ->placeholder('misal: 08123456789 atau 628123456789')
                            ->helperText('Sistem otomatis mendeteksi nomor HP di dalam isi pesan jika tersedia.')
                            ->default(function (Message $record): ?string {
                                if (preg_match('/(?:\+?62|0)[0-9\s\-]{8,14}/', $record->message, $matches)) {
                                    return trim($matches[0]);
                                }

                                return null;
                            }),
                        Textarea::make('whatsapp_text')
                            ->label('Pesan Balasan WA')
                            ->rows(4)
                            ->required()
                            ->default(fn (Message $record) => "Halo {$record->name},\n\nTerima kasih telah menghubungi portofolio saya mengenai: \"{$record->subject}\".\n\n"),
                    ])
                    ->action(function (Message $record, array $data, $livewire): void {
                        if (empty($data['phone_number'])) {
                            Notification::make()
                                ->title('Nomor WhatsApp tidak ditemukan')
                                ->body('Pengirim tidak menyantumkan nomor WhatsApp. Harap gunakan fitur "Balas via Email" untuk merespons.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $cleanPhone = preg_replace('/[^0-9]/', '', $data['phone_number']);
                        if (str_starts_with($cleanPhone, '0')) {
                            $cleanPhone = '62' . substr($cleanPhone, 1);
                        }
                        $encodedText = urlencode($data['whatsapp_text']);
                        $waUrl = "https://wa.me/{$cleanPhone}?text={$encodedText}";

                        $record->update([
                            'is_read' => true,
                            'replied_at' => now(),
                        ]);

                        $livewire->js("window.open('{$waUrl}', '_blank');");
                    })
                    ->modalSubmitActionLabel('Buka WhatsApp'),

                Action::make('markAsRead')
                    ->label('Tandai Dibaca')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->visible(fn (Message $record) => ! $record->is_read)
                    ->action(fn (Message $record) => $record->update(['is_read' => true])),

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
            'index' => Pages\ManageMessages::route('/'),
        ];
    }
}