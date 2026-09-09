<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
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

        // Base query: logbooks owned by peserta under this pembimbing
        $logbookQuery = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('pembimbing_id', $user->id);
        });

        $stats = [
            'peserta_aktif' => User::where('pembimbing_id', $user->id)
                ->whereHas('role', function ($q) {
                    $q->where('name', 'Internship');
                })
                ->count(),
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
