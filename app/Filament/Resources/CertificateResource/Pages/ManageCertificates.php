<?php

namespace App\Filament\Resources\CertificateResource\Pages;

use App\Filament\Resources\CertificateResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

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
                    $data['portfolio_profile_id'] = auth()->user()->portfolioProfile->id;

                    return $data;
                }),
        ];
    }
}
