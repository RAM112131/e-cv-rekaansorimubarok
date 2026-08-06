<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

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
                    $data['user_id'] = Auth::id() ?? 1;

                    return $data;
                }),
        ];
    }
}
