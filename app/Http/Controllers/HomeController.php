<?php

namespace App\Http\Controllers;

use App\Services\HomePageService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private HomePageService $homePageService) {}

    public function index(): View
    {
        return view('welcome', $this->homePageService->getViewData());
    }
}
