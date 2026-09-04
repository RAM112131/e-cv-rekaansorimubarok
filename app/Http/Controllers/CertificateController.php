<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateCategory;
use App\Models\User;

class CertificateController extends Controller
{
    /**
     * Halaman listing semua sertifikat — /certificates
     */
    public function index()
    {
        $certificates = Certificate::with('certificateCategory')->orderBy('issue_date', 'desc')->get();
        $categories   = CertificateCategory::orderBy('order', 'asc')->get();
        $user         = User::with('socialLinks')->first();
        $total        = $certificates->count();

        return view('certificates.index', compact('certificates', 'categories', 'user', 'total'));
    }
}
