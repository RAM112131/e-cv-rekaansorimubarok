<?php

namespace App\Http\Controllers;

use App\Services\ProjectPageService;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(private ProjectPageService $projectPageService) {}

    public function index(): View
    {
        return view('projects.index', $this->projectPageService->getIndexData());
    }

    public function show(string $slug): View
    {
        return view('projects.show', $this->projectPageService->getShowData($slug));
    }
}
