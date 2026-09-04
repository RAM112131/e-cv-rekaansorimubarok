<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    /**
     * Halaman listing semua project — /projects
     */
    public function index()
    {
        $projects = Project::where('is_published', true)
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();

        $user       = User::with('socialLinks')->first();
        $categories = $projects->pluck('category')->filter()->unique()->values();
        $total      = $projects->count();

        return view('projects.index', compact('projects', 'user', 'categories', 'total'));
    }

    /**
     * Halaman detail project — /project/{slug}
     */
    public function show(string $slug)
    {
        $project = Project::where('slug', $slug)
                          ->where('is_published', true)
                          ->firstOrFail();

        // 3 project lain sebagai "related" (exclude current)
        $related = Project::where('is_published', true)
                ->where('id', '!=', $project->id)
                ->where('category', $project->category)
                ->orderBy('order')
                ->take(3)
                ->get()
                ->whenEmpty(fn ($c) => Project::where('is_published', true)
                    ->where('id', '!=', $project->id)
                    ->orderBy('order')
                    ->take(3)
                    ->get()
                );

        $user = User::with('socialLinks')->first();

        return view('projects.show', compact('project', 'related', 'user'));
    }
}
