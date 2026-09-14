<?php

namespace App\Http\Controllers\Internship;

use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
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

        // Fetch active assignment with relations
        $assignment = InternshipAssignment::where('internship_id', $user->id)
            ->where('is_active', true)
            ->with(['division', 'mentor'])
            ->first();

        // Calculate progress
        $currentDay = 0;
        $totalDays = 0;
        $percentage = 0;
        $startDate = null;
        $endDate = null;
        $status = 'belum_dimulai';
        $isAutoFilled = false;

        if ($assignment) {
            // Use assignment dates, or fallback to first logbook
            $startDate = $assignment->start_date;
            $totalDays = $assignment->duration_days;

            if (!$startDate) {
                // Auto-fill: get first logbook clock_in as start date
                $firstLogbook = Logbook::where('user_id', $user->id)
                    ->orderBy('clock_in')
                    ->first();
                if ($firstLogbook) {
                    $startDate = $firstLogbook->clock_in;
                    $isAutoFilled = true;
                }
            }

            if (!$totalDays) {
                $totalDays = 90;
                $isAutoFilled = true;
            }

            if ($startDate) {
                $endDate = $startDate->copy()->addDays($totalDays - 1);
                $daysSinceStart = now()->diffInDays($startDate, false);
                $currentDay = max(0, $daysSinceStart + 1);

                // Clamp
                if ($currentDay > $totalDays) {
                    $currentDay = $totalDays;
                }

                $percentage = $totalDays > 0 ? min(100, max(0, round(($currentDay / $totalDays) * 100))) : 0;

                // Determine status
                if ($currentDay <= 0) {
                    $status = 'belum_dimulai';
                } elseif ($currentDay >= $totalDays) {
                    $status = 'selesai';
                } elseif ($percentage >= 75) {
                    $status = 'hampir_selesai';
                } else {
                    $status = 'berjalan';
                }
            }
        }

        return view('internship.logbook.dashboard', compact(
            'todayLogbook',
            'assignment',
            'currentDay',
            'totalDays',
            'percentage',
            'startDate',
            'endDate',
            'status',
            'isAutoFilled'
        ));
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
