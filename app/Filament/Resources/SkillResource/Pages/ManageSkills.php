<?php

namespace App\Filament\Resources\SkillResource\Pages;

use App\Filament\Resources\SkillResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Support\Facades\Auth;

class ManageSkills extends ManageRecords
{
    protected static string $resource = SkillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tambah Skill / Keahlian')
                ->icon('heroicon-o-plus')
                ->modalWidth('5xl')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = Auth::id() ?? 1;

                    return $data;
                }),
        ];
    }
}
