<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\CertificateCategory;
use App\Models\PortfolioProfile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CertificatePageService
{
    /**
     * @return array{certificates: Collection, categories: Collection, user: object, total: int}
     */
    public function getIndexData(): array
    {
        $content = Cache::flexible('portfolio.certificates.index.v3', [300, 900], function (): array {
            $profile = PortfolioProfile::query()->firstOrFail();

            return [
                'profile_name' => $profile->name,
                'certificates' => Certificate::query()
                    ->select([
                        'id',
                        'portfolio_profile_id',
                        'certificate_category_id',
                        'title',
                        'image',
                        'issuer',
                        'credential_id',
                        'issue_date',
                        'expiration_date',
                        'credential_url',
                    ])
                    ->where('portfolio_profile_id', $profile->id)
                    ->where('is_published', true)
                    ->with('certificateCategory:id,name')
                    ->orderByDesc('issue_date')
                    ->get()
                    ->toArray(),
                'categories' => CertificateCategory::query()
                    ->select(['id', 'portfolio_profile_id', 'name', 'order'])
                    ->where('portfolio_profile_id', $profile->id)
                    ->orderBy('order')
                    ->get()
                    ->toArray(),
            ];
        });

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
                'name' => $content['profile_name'],
            ],
            'total' => $certificates->count(),
        ];
    }
}
