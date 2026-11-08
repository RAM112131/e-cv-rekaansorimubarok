<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateCategory;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CertificatePageService
{
    /**
     * @return array{certificates: Collection, categories: Collection, user: object, total: int}
     */
    public function getIndexData(): array
    {
        $content = Cache::flexible('portfolio.certificates.index.v2', [300, 900], fn (): array => [
            'certificates' => Certificate::query()
                ->select([
                    'id',
                    'certificate_category_id',
                    'title',
                    'image',
                    'issuer',
                    'credential_id',
                    'issue_date',
                    'expiration_date',
                    'credential_url',
                ])
                ->with('certificateCategory:id,name')
                ->orderByDesc('issue_date')
                ->get()
                ->toArray(),
            'categories' => CertificateCategory::query()
                ->select(['id', 'name', 'order'])
                ->orderBy('order')
                ->get()
                ->toArray(),
        ]);

        $certificates = collect($content['certificates'])
            ->map(function (array $certificate): object {
                $certificateCategory = $certificate['certificate_category'] ?? null;
                unset($certificate['certificate_category']);

                $certificate['certificateCategory'] = $certificateCategory
                    ? (object) $certificateCategory
                    : null;

                return (object) $certificate;
            });

        return [
            'certificates' => $certificates,
            'categories' => collect($content['categories'])->map(fn (array $category) => (object) $category),
            'user' => (object) [
                'name' => Cache::flexible(
                    'portfolio.public_profile_name',
                    [300, 900],
                    fn (): ?string => User::query()->value('name'),
                ),
            ],
            'total' => $certificates->count(),
        ];
    }
}
