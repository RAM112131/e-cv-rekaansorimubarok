<?php

namespace App\Services;

use App\Models\Education;
use App\Models\PortfolioProfile;
use App\Models\Skill;
use App\Models\SocialLink;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class CvPageService
{
    /**
     * @return array{
     *     user: PortfolioProfile,
     *     educations: EloquentCollection<int, Education>,
     *     skills: EloquentCollection<int, Skill>,
     *     socialLinks: EloquentCollection<int, SocialLink>
     * }|null
     */
    public function getPreviewData(): ?array
    {
        $data = Cache::flexible('portfolio.cv_preview.v3', [300, 900], function (): ?array {
            $profile = PortfolioProfile::query()
                ->with([
                    'educations',
                    'socialLinks',
                    'skills',
                    'cvLanguages',
                    'certificates' => fn ($query) => $query
                        ->where('is_published', true)
                        ->where('show_on_cv', true)
                        ->orderBy('order'),
                    'cvEntries' => fn ($query) => $query->with([
                        'project:id,portfolio_profile_id,title,year,role,description,created_at',
                        'experience:id,portfolio_profile_id,title,company,start_date,end_date,is_current,description',
                    ]),
                ])
                ->first();

            if ($profile === null) {
                return null;
            }

            return [
                'profile' => $profile->getAttributes(),
                'educations' => $profile->educations->toArray(),
                'social_links' => $profile->socialLinks->toArray(),
                'skills' => $profile->skills->toArray(),
                'languages' => $profile->cvLanguages->toArray(),
                'certificates' => $profile->certificates->toArray(),
                'entries' => $profile->cvEntries->toArray(),
            ];
        });

        if ($data === null) {
            return null;
        }

        $user = (new PortfolioProfile)->forceFill($data['profile']);
        $educations = Education::hydrate($data['educations']);
        $socialLinks = SocialLink::hydrate($data['social_links']);
        $skills = Skill::hydrate($data['skills']);
        $user->setRelation('educations', $educations);
        $user->setRelation('socialLinks', $socialLinks);

        $linkedinUrl = $socialLinks->first(
            fn (SocialLink $socialLink): bool => str_contains(strtolower($socialLink->platform), 'linkedin'),
        )?->url;

        $user->setAttribute('linkedin_url', $linkedinUrl);
        $user->setAttribute('cv_data', [
            'cv_name' => $user->name,
            'cv_languages' => collect($data['languages'])
                ->map(fn (array $language): array => [
                    'name' => $language['name'],
                    'rating' => $language['rating'],
                ])
                ->all(),
            'cv_certifications' => collect($data['certificates'])
                ->map(fn (array $certificate): array => [
                    'name' => $certificate['title'],
                    'issuer' => $certificate['issuer'],
                    'year' => $certificate['issue_date']
                        ? date('Y', strtotime($certificate['issue_date']))
                        : null,
                ])
                ->all(),
            'cv_projects' => $this->projectAndExperienceEntries($data['entries']),
            'cv_orgs' => $this->organizationEntries($data['entries']),
        ]);

        return [
            'user' => $user,
            'educations' => $educations,
            'skills' => $skills,
            'socialLinks' => $socialLinks,
        ];
    }

    /**
     * @return array{file: string, download_name: string}|null
     */
    public function getDownloadFile(): ?array
    {
        $data = Cache::flexible('portfolio.cv_download.v3', [300, 900], fn (): ?array => PortfolioProfile::query()
            ->whereNotNull('cv_file')
            ->where('cv_file', '!=', '')
            ->first(['cv_file', 'name'])
            ?->only(['cv_file', 'name']));

        if ($data === null || ! Storage::disk('public')->exists($data['cv_file'])) {
            return null;
        }

        $filename = 'CV-'.$data['name'].'.'.$this->extension($data['cv_file']);

        return [
            'file' => $data['cv_file'],
            'download_name' => $filename,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function projectAndExperienceEntries(array $entries): array
    {
        return collect($entries)
            ->filter(fn (array $entry): bool => in_array($entry['entry_type'], ['project', 'experience'], true))
            ->map(function (array $entry): array {
                $project = $entry['project'] ?? null;
                $experience = $entry['experience'] ?? null;

                if ($entry['entry_type'] === 'project' && $project !== null) {
                    return [
                        'name' => $entry['title'] ?: $project['title'],
                        'badge' => $entry['period'] ?: ($project['year'] ?: null),
                        'sub' => $entry['subtitle'] ?: $project['role'],
                        'desc' => $entry['description'] ?: $project['description'],
                    ];
                }

                if ($experience !== null) {
                    return [
                        'name' => $entry['title'] ?: $experience['title'],
                        'badge' => $entry['period'] ?: $this->experiencePeriod($experience),
                        'sub' => $entry['subtitle'] ?: $experience['company'],
                        'desc' => $entry['description'] ?: $experience['description'],
                    ];
                }

                return [
                    'name' => $entry['title'],
                    'badge' => $entry['period'],
                    'sub' => $entry['subtitle'],
                    'desc' => $entry['description'],
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $entries
     * @return array<int, array<string, mixed>>
     */
    private function organizationEntries(array $entries): array
    {
        return collect($entries)
            ->where('entry_type', 'organization')
            ->map(fn (array $entry): array => [
                'name' => $entry['title'],
                'badge' => $entry['period'],
                'sub' => $entry['subtitle'],
                'desc' => $entry['description'],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $experience
     */
    private function experiencePeriod(array $experience): string
    {
        $startDate = $experience['start_date'] ?? null;
        $endDate = ! empty($experience['is_current'])
            ? 'Sekarang'
            : ($experience['end_date'] ?? null);

        if ($startDate === null) {
            return '';
        }

        $start = date('M Y', strtotime($startDate));
        $end = $endDate === 'Sekarang'
            ? $endDate
            : ($endDate ? date('M Y', strtotime($endDate)) : '');

        return $end === '' ? $start : "{$start} – {$end}";
    }

    private function extension(string $path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION) ?: 'pdf';
    }
}
