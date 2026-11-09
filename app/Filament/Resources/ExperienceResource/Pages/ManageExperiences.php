<?php

namespace App\Filament\Resources\ExperienceResource\Pages;

use App\Filament\Resources\ExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageExperiences extends ManageRecords
{
    protected static string $resource = ExperienceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Pengalaman')
                ->icon('heroicon-o-plus')
                ->modalWidth('5xl')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['portfolio_profile_id'] = auth()->user()->portfolioProfile->id;

                    return $data;
                }),
        ];
    }
}
