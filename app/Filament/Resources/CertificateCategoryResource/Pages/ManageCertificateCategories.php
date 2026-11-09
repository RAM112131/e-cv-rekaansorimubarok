<?php

namespace App\Filament\Resources\CertificateCategoryResource\Pages;

use App\Filament\Resources\CertificateCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCertificateCategories extends ManageRecords
{
    protected static string $resource = CertificateCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Tambah Kategori Sertifikat')
                ->modalWidth('md')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['portfolio_profile_id'] = auth()->user()->portfolioProfile->id;

                    return $data;
                }),
        ];
    }
}
