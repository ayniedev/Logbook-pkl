<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\InternshipAssignment;
use App\Models\Logbook;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleName = optional($user)->role?->name;

        if ($roleName !== 'Admin') {
            return redirect()->route($this->getDashboardRoute($roleName));
        }

        $stats = [
            'total_users' => User::count(),
            'total_mentors' => User::whereHas('role', fn($q) => $q->where('name', 'Pembimbing'))->count(),
            'total_interns' => User::whereHas('role', fn($q) => $q->where('name', 'Internship'))->count(),
            'total_divisions' => Division::count(),
            'active_assignments' => InternshipAssignment::where('is_active', true)->count(),
            'pending_logbooks' => Logbook::where('approval_status', 'Diajukan')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    private function getDashboardRoute(?string $roleName): string
    {
        return match ($roleName) {
            'Internship' => 'internship.dashboard',
            'Pembimbing' => 'pembimbing.dashboard',
            default => 'login',
        };
    }
}