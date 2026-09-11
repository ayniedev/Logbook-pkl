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
        $assignedInternIds = InternshipAssignment::where('mentor_id', $user->id)
            ->where('is_active', true)
            ->pluck('internship_id')
            ->toArray();

        // Base query: logbooks owned by assigned peserta
        $logbookQuery = Logbook::whereIn('user_id', $assignedInternIds);

        $stats = [
            'peserta_aktif' => count($assignedInternIds),
            'menunggu' => (clone $logbookQuery)->where('approval_status', 'Diajukan')->count(),
            'disetujui' => (clone $logbookQuery)->where('approval_status', 'Disetujui')->count(),
            'ditolak' => (clone $logbookQuery)->where('approval_status', 'Ditolak')->count(),
        ];

        $pendingLogbooks = (clone $logbookQuery)
            ->where('approval_status', 'Diajukan')
            ->with(['user', 'project'])
            ->orderBy('clock_in')
            ->limit(10)
            ->get();

        return view('pembimbing.dashboard', compact('stats', 'pendingLogbooks'));
    }
}
