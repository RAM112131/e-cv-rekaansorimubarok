<?php

namespace App\Http\Controllers;

use App\Services\CertificatePageService;
use Illuminate\View\View;

class CertificateController extends Controller
{
    public function __construct(private CertificatePageService $certificatePageService) {}

    public function index(): View
    {
        return view('certificates.index', $this->certificatePageService->getIndexData());
    }
}
