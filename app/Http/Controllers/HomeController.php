<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // ─── Cache semua data homepage (TTL: 10 menit) ─────────────────
        // Cache ini otomatis invalid ketika ada perubahan data via Filament
        // dengan memanggil HomeController::clearCache()
        $data = Cache::remember('homepage_data', 600, function () {
            return static::fetchHomepageData();
        });

        // ─── Auto-heal jika cache berisi object yang corrupt / __PHP_Incomplete_Class ───
        if (! isset($data['user']) || ! ($data['user'] instanceof User)) {
            static::clearCache();
            $data = static::fetchHomepageData();
        }

        return view('welcome', $data);
    }

    /**
     * Fetch fresh homepage data from database.
     */
    public static function fetchHomepageData(): array
    {
        $skills = Skill::orderBy('category', 'asc')->get();
        $groupedSkills = $skills->groupBy('category');

        $experiences       = Experience::orderBy('start_date', 'desc')->get();
        $totalProjects     = Project::where('is_published', true)->count();
        $projects          = Project::where('is_published', true)
                                ->orderBy('order', 'asc')
                                ->orderBy('created_at', 'desc')
                                ->take(6)
                                ->get();
        $certificates      = Certificate::with('certificateCategory')
                                ->orderBy('order', 'asc')
                                ->orderBy('created_at', 'desc')
                                ->orderBy('issue_date', 'desc')
                                ->take(6)
                                ->get();
        $totalCertificates = Certificate::count();

        $user = User::with(['socialLinks'])->first();

        $names = explode(' ', $user->name ?? 'Rayhan Aditya');
        $count = count($names);
        if ($count > 1) {
            $firstName = implode(' ', array_slice($names, 0, $count - 1));
            $lastName  = $names[$count - 1];
        } else {
            $firstName = $names[0] ?? 'RAYHAN';
            $lastName  = '';
        }

        // Hitung stats sekaligus dari data yang sudah di-load
        $oldestExperience   = $experiences->min('start_date');
        $yearsExperience    = $oldestExperience
            ? abs((int) \Carbon\Carbon::parse($oldestExperience)->diffInYears(now()))
            : 0;
        $uniqueCategories   = $projects->pluck('category')->unique()->count();
        $totalClients       = ($uniqueCategories * 5) + $totalProjects;

        return compact(
            'groupedSkills',
            'experiences',
            'projects',
            'certificates',
            'totalCertificates',
            'user',
            'firstName',
            'lastName',
            'yearsExperience',
            'totalProjects',
            'totalClients'
        );
    }

    /**
     * Hapus cache homepage — dipanggil setelah update data via admin.
     */
    public static function clearCache(): void
    {
        Cache::forget('homepage_data');
    }
}
