<?php

namespace App\Services;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProjectPageService
{
    /**
     * @return array{projects: Collection, user: object, categories: Collection, total: int}
     */
    public function getIndexData(): array
    {
        $projectData = Cache::flexible('portfolio.projects.index.v2', [300, 900], fn (): array => Project::query()
            ->select(['id', 'title', 'slug', 'description', 'image', 'category', 'tech_stack', 'order', 'created_at'])
            ->where('is_published', true)
            ->orderBy('order')
            ->orderByDesc('created_at')
            ->get()
            ->toArray());
        $projects = collect($projectData)->map(fn (array $project) => (object) $project);
        $user = (object) [
            'name' => Cache::flexible(
                'portfolio.public_profile_name',
                [300, 900],
                fn (): ?string => User::query()->value('name'),
            ),
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
        $project = Project::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $related = Project::query()
            ->where('is_published', true)
            ->where('id', '!=', $project->id)
            ->where('category', $project->category)
            ->orderBy('order')
            ->take(3)
            ->get()
            ->whenEmpty(fn (Collection $projects): Collection => Project::query()
                ->where('is_published', true)
                ->where('id', '!=', $project->id)
                ->orderBy('order')
                ->take(3)
                ->get());

        $user = (object) [
            'name' => Cache::flexible(
                'portfolio.public_profile_name',
                [300, 900],
                fn (): ?string => User::query()->value('name'),
            ),
        ];

        return compact('project', 'related', 'user');
    }
}
