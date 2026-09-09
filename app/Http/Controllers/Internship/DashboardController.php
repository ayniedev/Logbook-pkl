<?php

namespace App\Http\Controllers\Internship;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $roleName = optional($user)->role?->name;

        if ($roleName !== 'Internship') {
            return redirect()->route($this->getDashboardRoute($roleName));
        }

        // Today's attendance record
        $todayLogbook = Logbook::where('user_id', $user->id)
            ->whereDate('clock_in', now()->toDateString())
            ->first();

        return view('internship.logbook.dashboard', compact('todayLogbook'));
    }

    private function getDashboardRoute(?string $roleName): string
    {
        return match ($roleName) {
            'Admin' => 'admin.dashboard',
            'Pembimbing' => 'pembimbing.dashboard',
            default => 'login',
        };
    }
}