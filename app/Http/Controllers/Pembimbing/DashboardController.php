<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\InternshipAssignment;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Constructor - restrict access to Pembimbing only.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            $roleName = optional($user)->role?->name;

            if ($roleName !== 'Pembimbing') {
                abort(403, 'Akses ditolak. Hanya Pembimbing yang dapat mengakses halaman ini.');
            }

            return $next($request);
        });
    }

    /**
     * Dashboard - statistics and logbooks that need review.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Get intern IDs assigned to this mentor via internship_assignments
        $assignments = InternshipAssignment::where('mentor_id', $user->id)
            ->where('is_active', true)
            ->with(['internship', 'division'])
            ->get();

        $assignedInternIds = $assignments->pluck('internship_id')->toArray();

        // Base query: logbooks owned by assigned peserta
        $logbookQuery = Logbook::whereIn('user_id', $assignedInternIds);

        $stats = [
            'peserta_aktif' => count($assignedInternIds),
            'menunggu' => (clone $logbookQuery)->where('approval_status', 'Diajukan')->count(),
            'disetujui' => (clone $logbookQuery)->where('approval_status', 'Disetujui')->count(),
            'ditolak' => (clone $logbookQuery)->where('approval_status', 'Ditolak')->count(),
        ];

        // Calculate progress for each assignment
        $internProgress = $assignments->map(function ($assignment) {
            $currentDay = 0;
            $totalDays = 0;
            $percentage = 0;
            $startDate = null;
            $endDate = null;
            $status = 'belum_dimulai';
            $isAutoFilled = false;

            // Use assignment dates, or fallback to first logbook
            $startDate = $assignment->start_date;
            $totalDays = $assignment->duration_days;

            if (!$startDate) {
                // Auto-fill: get first logbook clock_in as start date
                $firstLogbook = Logbook::where('user_id', $assignment->internship_id)
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

                if ($currentDay > $totalDays) {
                    $currentDay = $totalDays;
                }

                $percentage = $totalDays > 0 ? min(100, max(0, round(($currentDay / $totalDays) * 100))) : 0;

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

            return [
                'intern_name' => $assignment->internship->name ?? $assignment->internship->username ?? '-',
                'division_name' => $assignment->division->name ?? '-',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_days' => $totalDays,
                'current_day' => $currentDay,
                'percentage' => $percentage,
                'status' => $status,
                'is_auto_filled' => $isAutoFilled,
            ];
        });

        $pendingLogbooks = (clone $logbookQuery)
            ->where('approval_status', 'Diajukan')
            ->with(['user', 'project'])
            ->orderBy('clock_in')
            ->limit(10)
            ->get();

        return view('pembimbing.dashboard', compact('stats', 'pendingLogbooks', 'internProgress'));
    }
}
