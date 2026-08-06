<?php

namespace App\Filament\Resources\ExperienceResource\Pages;

use App\Filament\Resources\ExperienceResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

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
                    $data['user_id'] = Auth::id() ?? 1;

                    return $data;
                }),
        ];
    }
}
