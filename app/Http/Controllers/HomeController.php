<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Experience;
use App\Models\Project;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('category', 'asc')->get();
        
        // Group skills by category
        $groupedSkills = $skills->groupBy('category');

        $experiences = Experience::orderBy('start_date', 'desc')->get();
        $projects = Project::orderBy('created_at', 'desc')->take(6)->get();
        $certificates = Certificate::orderBy('issue_date', 'desc')->get();
        
        $user = User::first(); // Assuming single user portofolio
        
        $names = explode(' ', $user->name ?? 'Rayhan Aditya');
        $count = count($names);
        if ($count > 1) {
            $firstName = implode(' ', array_slice($names, 0, $count - 1));
            $lastName = $names[$count - 1];
        } else {
            $firstName = $names[0] ?? 'RAYHAN';
            $lastName = '';
        }

        $yearsExperience = Experience::min('start_date') ? abs((int) \Carbon\Carbon::parse(Experience::min('start_date'))->diffInYears(now())) : 0;
        $totalProjects = Project::count();
        $totalClients = Project::distinct('category')->count() * 5 + $totalProjects; // Mocking a reasonable client number if no specific field exists

        return view('welcome', compact(
            'groupedSkills',
            'experiences',
            'projects',
            'certificates',
            'user',
            'firstName',
            'lastName',
            'yearsExperience',
            'totalProjects',
            'totalClients'
        ));
    }
}
