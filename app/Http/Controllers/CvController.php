<?php

namespace App\Http\Controllers;

use App\Services\CvPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CvController extends Controller
{
    public function __construct(private CvPageService $cvPageService) {}

    public function preview(): View
    {
        $data = $this->cvPageService->getPreviewData();

        abort_if($data === null, 404, 'User profile not found');

        return view('cv', $data);
    }

    public function download(): StreamedResponse|RedirectResponse
    {
        $file = $this->cvPageService->getDownloadFile();

        if ($file === null) {
            return redirect()->route('cv.preview');
        }

        return $this->cvPageService->download($file);
    }
}
