<?php

namespace App\Services;

use App\Models\PortfolioProfile;
use App\Models\Project;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProjectPageService
{
    /**
     * @return array{projects: Collection, user: object, categories: Collection, total: int}
     */
    public function getIndexData(): array
    {
        $projectData = Cache::flexible('portfolio.projects.index.v3', [300, 86400], function (): array {
            $profile = PortfolioProfile::query()->firstOrFail();

            return [
                'profile_name' => $profile->name,
                'projects' => Project::query()
                    ->select(['id', 'portfolio_profile_id', 'title', 'slug', 'description', 'image', 'category', 'tech_stack', 'order', 'created_at'])
                    ->where('portfolio_profile_id', $profile->id)
                    ->where('is_published', true)
                    ->orderBy('order')
                    ->orderByDesc('created_at')
                    ->get()
                    ->toArray(),
            ];
        });
        $projects = collect($projectData['projects'])->map(fn (array $project) => (object) $project);
        $user = (object) [
            'name' => $projectData['profile_name'],
        ];

        return [
            'projects' => $projects,
            'user' => $user,
            'categories' => $projects->pluck('category')->filter()->unique()->values(),
            'total' => $projects->count(),
        ];
    }

    /**
     * @return array{project: Project, related: Collection, user: object}
     */
    public function getShowData(string $slug): array
    {
        $profile = PortfolioProfile::query()->firstOrFail();

        $project = Project::query()
            ->where('portfolio_profile_id', $profile->id)
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Project::query()
            ->where('portfolio_profile_id', $profile->id)
            ->where('is_published', true)
            ->where('id', '!=', $project->id)
            ->where('category', $project->category)
            ->orderBy('order')
            ->take(3)
            ->get()
            ->whenEmpty(fn (Collection $projects): Collection => Project::query()
                ->where('portfolio_profile_id', $profile->id)
                ->where('is_published', true)
                ->where('id', '!=', $project->id)
                ->orderBy('order')
                ->take(3)
                ->get());

        $user = (object) [
            'name' => $profile->name,
        ];

        return compact('project', 'related', 'user');
    }
}
