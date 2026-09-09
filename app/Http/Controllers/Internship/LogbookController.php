<?php

namespace App\Http\Controllers\Internship;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogbookController extends Controller
{
    /**
     * Constructor - restrict access to Internship only.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            $roleName = optional($user)->role?->name;

            if ($roleName !== 'Internship') {
                abort(403, 'Akses ditolak. Hanya Internship yang dapat mengakses halaman ini.');
            }

            return $next($request);
        });
    }

    /**
     * Dashboard - show attendance status and buttons.
     */
    public function dashboard()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Get today's logbook record
        $todayLogbook = Logbook::where('user_id', $user->id)
            ->whereDate('clock_in', $today)
            ->first();

        return view('internship.logbook.dashboard', compact('todayLogbook'));
    }

    /**
     * Clock In - record attendance start.
     */
    public function clockIn()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Check if already clocked in today
        $existingLogbook = Logbook::where('user_id', $user->id)
            ->whereDate('clock_in', $today)
            ->first();

        if ($existingLogbook) {
            return redirect()->route('internship.dashboard')
                ->with('error', 'Anda sudah melakukan absen masuk hari ini.');
        }

        // Create new logbook with clock_in
        Logbook::create([
            'user_id' => $user->id,
            'clock_in' => now(),
            'approval_status' => 'Pending',
        ]);

        return redirect()->route('internship.dashboard')
            ->with('success', 'Absen masuk berhasil!');
    }

    /**
     * Clock Out - record attendance end and redirect to logbook form.
     */
    public function clockOut()
    {
        $user = Auth::user();
        $today = now()->toDateString();

        // Get today's logbook
        $logbook = Logbook::where('user_id', $user->id)
            ->whereDate('clock_in', $today)
            ->whereNull('clock_out')
            ->first();

        if (!$logbook) {
            return redirect()->route('internship.dashboard')
                ->with('error', 'Tidak ada absen masuk yang perlu diakhiri.');
        }

        // Update clock_out
        $logbook->update([
            'clock_out' => now(),
        ]);

        // Redirect to logbook form
        return redirect()->route('internship.logbook.create', $logbook->id)
            ->with('success', 'Absen keluar berhasil! Silakan isi logbook.');
    }

    /**
     * Show form to fill logbook details.
     */
    public function create(Logbook $logbook)
    {
        // Ensure user owns this logbook
        if ($logbook->user_id !== Auth::id()) {
            abort(403);
        }

        $projects = Project::where('is_active', true)->get();

        return view('internship.logbook.create', compact('logbook', 'projects'));
    }

    /**
     * Store logbook details.
     */
    public function store(Request $request, Logbook $logbook)
    {
        // Ensure user owns this logbook
        if ($logbook->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'project_id' => ['nullable', 'exists:projects,id'],
            'activities' => ['required', 'string'],
            'result' => ['required', 'string'],
        ]);

        $logbook->update([
            'project_id' => $validated['project_id'] ?? null,
            'activities' => $validated['activities'],
            'result' => $validated['result'],
            'approval_status' => 'Diajukan',
        ]);

        return redirect()->route('internship.logbook.history')
            ->with('success', 'Logbook berhasil disubmit!');
    }

    /**
     * Show logbook history.
     */
    public function history()
    {
        $user = Auth::user();

        $logbooks = Logbook::where('user_id', $user->id)
            ->with('project')
            ->latest('clock_in')
            ->paginate(10);

        return view('internship.logbook.history', compact('logbooks'));
    }
}
