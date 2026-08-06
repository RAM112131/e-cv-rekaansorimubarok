<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Resources\CertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageCertificates extends ManageRecords
{
    protected static string $resource = CertificateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Sertifikat')
                ->icon('heroicon-o-plus')
                ->modalWidth('5xl')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::id() ?? 1;

                    return $data;
                }),
        ];
    }
}
