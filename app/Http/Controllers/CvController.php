<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    public function preview()
    {
        $user = User::where('role', 'admin')->with(['educations', 'socialLinks'])->first() ?? User::with(['educations', 'socialLinks'])->first();

        if (! $user) {
            abort(404, 'User profile not found');
        }

        $educations = $user->educations;
        $skills = Skill::orderBy('order')->get();
        $socialLinks = $user->socialLinks;

        return view('cv', compact(
            'user',
            'educations',
            'skills',
            'socialLinks'
        ));
    }

    public function download()
    {
        $user = User::where('role', 'admin')->with(['educations', 'socialLinks'])->first() ?? User::with(['educations', 'socialLinks'])->first();

        if ($user && $user->cv_file && Storage::disk('public')->exists($user->cv_file)) {
            return Storage::disk('public')->download($user->cv_file, 'CV_' . str_replace(' ', '_', $user->name) . '.pdf');
        }

        // Return preview page if no custom PDF file is uploaded
        return redirect()->route('cv.preview');
    }
}
