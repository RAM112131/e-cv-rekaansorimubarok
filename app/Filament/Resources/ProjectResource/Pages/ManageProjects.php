<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageProjects extends ManageRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Proyek Baru')
                ->icon('heroicon-o-plus')
                ->modalWidth('6xl')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['portfolio_profile_id'] = auth()->user()->portfolioProfile->id;

                    return $data;
                }),
        ];
    }
}
