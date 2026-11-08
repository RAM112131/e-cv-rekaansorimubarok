<?php

namespace App\Services;

use App\Models\Education;
use App\Models\Skill;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvPageService
{
    /**
     * @return array{user: User, educations: mixed, skills: mixed, socialLinks: mixed}|null
     */
    public function getPreviewData(): ?array
    {
        $userData = Cache::flexible('portfolio.cv_profile.v2', [300, 900], fn (): ?array => (
            User::query()
                ->where('role', 'admin')
                ->with(['educations', 'socialLinks'])
                ->first() ?? User::query()->with(['educations', 'socialLinks'])->first()
        )?->toArray());

        if ($userData === null) {
            return null;
        }

        $educationData = $userData['educations'] ?? [];
        $socialLinkData = $userData['social_links'] ?? [];
        unset($userData['educations'], $userData['social_links']);

        $user = (new User)->forceFill($userData);
        $user->setRelation('educations', Education::hydrate($educationData));
        $user->setRelation('socialLinks', SocialLink::hydrate($socialLinkData));

        $skillData = Cache::flexible('portfolio.cv_skills.v2', [300, 900], fn (): array => Skill::query()
            ->select(['id', 'name', 'level', 'order'])
            ->orderBy('order')
            ->get()
            ->toArray());

        return [
            'user' => $user,
            'educations' => $user->educations,
            'skills' => collect($skillData)->map(fn (array $skill) => (object) $skill),
            'socialLinks' => $user->socialLinks,
        ];
    }

    /**
     * @return array{path: string, filename: string}|null
     */
    public function getDownloadFile(): ?array
    {
        $userData = Cache::flexible('portfolio.cv_download_profile.v2', [300, 900], fn (): ?array => (
            User::query()
                ->select(['id', 'name', 'cv_file', 'role'])
                ->where('role', 'admin')
                ->first() ?? User::query()->select(['id', 'name', 'cv_file', 'role'])->first()
        )?->toArray());

        if (! $userData || ! $userData['cv_file'] || ! Storage::disk('public')->exists($userData['cv_file'])) {
            return null;
        }

        return [
            'path' => $userData['cv_file'],
            'filename' => 'CV_'.str_replace(' ', '_', $userData['name']).'.pdf',
        ];
    }

    /**
     * @param  array{path: string, filename: string}  $file
     */
    public function download(array $file): StreamedResponse
    {
        return Storage::disk('public')->download($file['path'], $file['filename']);
    }
}
