<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class HomePageService
{
    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        $data = Cache::flexible('homepage_data.v2', [300, 900], fn (): array => $this->fetchHomepageData());

        return $this->hydrateHomepageData($data);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchHomepageData(): array
    {
        $skills = Skill::query()->orderBy('category')->get();
        $groupedSkills = $skills->groupBy('category')
            ->map(fn ($categorySkills) => $categorySkills->toArray())
            ->toArray();

        $experiences = Experience::query()->orderByDesc('start_date')->get();
        $totalProjects = Project::query()->where('is_published', true)->count();
        $projects = Project::query()
            ->where('is_published', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();
        $certificates = Certificate::query()
            ->with('certificateCategory')
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->orderByDesc('issue_date')
            ->take(3)
            ->get();
        $totalCertificates = Certificate::query()->count();
        $user = User::query()->with('socialLinks')->first();

        $names = explode(' ', $user->name ?? 'Rayhan Aditya');
        $count = count($names);
        $firstName = $count > 1
            ? implode(' ', array_slice($names, 0, $count - 1))
            : ($names[0] ?? 'RAYHAN');
        $lastName = $count > 1 ? $names[$count - 1] : '';

        $oldestExperience = $experiences->min('start_date');
        $yearsExperience = $oldestExperience
            ? abs((int) Carbon::parse($oldestExperience)->diffInYears(now()))
            : 0;

        return [
            'groupedSkills' => $groupedSkills,
            'experiences' => $experiences->toArray(),
            'projects' => $projects->toArray(),
            'certificates' => $certificates->toArray(),
            'totalCertificates' => $totalCertificates,
            'user' => $user?->toArray(),
            'firstName' => $firstName,
            'lastName' => $lastName,
            'yearsExperience' => $yearsExperience,
            'totalProjects' => $totalProjects,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    private function hydrateHomepageData(array $data): array
    {
        $data['groupedSkills'] = collect($data['groupedSkills'])
            ->map(fn (array $skills) => collect($skills)->map(fn (array $skill) => (object) $skill));
        $data['experiences'] = collect($data['experiences'])
            ->map(fn (array $experience) => (object) $experience);
        $data['projects'] = collect($data['projects'])
            ->map(fn (array $project) => (object) $project);
        $data['certificates'] = collect($data['certificates'])
            ->map(function (array $certificate): object {
                $certificateCategory = $certificate['certificate_category'] ?? null;
                unset($certificate['certificate_category']);

                $certificate['category'] ??= null;
                $certificate['description'] ??= null;
                $certificate['certificateCategory'] = $certificateCategory
                    ? (object) $certificateCategory
                    : null;

                return (object) $certificate;
            });

        if ($data['user'] !== null) {
            $userData = $data['user'];
            $socialLinks = $userData['social_links'] ?? [];
            unset($userData['social_links']);

            $user = (new User)->forceFill($userData);
            $user->setRelation('socialLinks', SocialLink::hydrate($socialLinks));
            $data['user'] = $user;
        }

        return $data;
    }
}
