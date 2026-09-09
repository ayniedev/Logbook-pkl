<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\Logbook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogbookController extends Controller
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
     * List of peserta (interns) under this pembimbing.
     */
    public function peserta()
    {
        $user = Auth::user();

        $peserta = User::where('pembimbing_id', $user->id)
            ->whereHas('role', function ($q) {
                $q->where('name', 'Internship');
            })
            ->withCount('logbooks')
            ->with('pembimbing')
            ->latest()
            ->paginate(10);

        return view('pembimbing.peserta', compact('peserta'));
    }

    /**
     * Logbooks of peserta under this pembimbing, with filters.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Only logbooks owned by peserta assigned to this pembimbing
        $query = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('pembimbing_id', $user->id);
        })->with(['user', 'project']);

        // Filter by status
        $status = $request->input('status');
        if (in_array($status, ['Diajukan', 'Disetujui', 'Ditolak'])) {
            $query->where('approval_status', $status);
        }

        // Filter by peserta
        if ($pesertaId = $request->input('peserta_id')) {
            // Ensure the selected peserta belongs to this pembimbing
            $validPeserta = User::where('pembimbing_id', $user->id)->where('id', $pesertaId)->exists();
            if ($validPeserta) {
                $query->where('user_id', $pesertaId);
            }
        }

        // Filter by date
        if ($tanggal = $request->input('tanggal')) {
            $query->whereDate('clock_in', $tanggal);
        }

        $logbooks = $query->orderByDesc('clock_in')->paginate(10)->withQueryString();

        $pesertaList = User::where('pembimbing_id', $user->id)
            ->whereHas('role', function ($q) {
                $q->where('name', 'Internship');
            })
            ->orderBy('name')
            ->get();

        return view('pembimbing.logbook.index', compact('logbooks', 'pesertaList', 'status'));
    }

    /**
     * Detail of a logbook - only if owned by peserta under this pembimbing.
     */
    public function show(Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        return view('pembimbing.logbook.detail', compact('logbook'));
    }

    /**
     * History - logbooks already reviewed (Disetujui / Ditolak).
     */
    public function riwayat(Request $request)
    {
        $user = Auth::user();

        $query = Logbook::whereHas('user', function ($q) use ($user) {
            $q->where('pembimbing_id', $user->id);
        })
            ->whereIn('approval_status', ['Disetujui', 'Ditolak'])
            ->with(['user', 'project', 'approver']);

        // Separate tab: Disetujui / Ditolak / both
        $tab = $request->input('tab');
        if (in_array($tab, ['Disetujui', 'Ditolak'])) {
            $query->where('approval_status', $tab);
        }

        $logbooks = $query->orderByDesc('approved_at')->paginate(10)->withQueryString();

        return view('pembimbing.riwayat', compact('logbooks', 'tab'));
    }

    /**
     * Approve a logbook.
     */
    public function approve(Request $request, Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        // Only pending/submitted logbooks can be approved
        if ($logbook->approval_status !== 'Diajukan') {
            return redirect()->route('pembimbing.logbook.show', $logbook->id)
                ->with('error', 'Logbook ini sudah diperiksa sebelumnya.');
        }

        $logbook->update([
            'approval_status' => 'Disetujui',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()->route('pembimbing.logbook.show', $logbook->id)
            ->with('success', 'Logbook berhasil disetujui.');
    }

    /**
     * Reject a logbook with a required reason.
     */
    public function reject(Request $request, Logbook $logbook)
    {
        $this->authorizeLogbook($logbook);

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        // Only pending/submitted logbooks can be rejected
        if ($logbook->approval_status !== 'Diajukan') {
            return redirect()->route('pembimbing.logbook.show', $logbook->id)
                ->with('error', 'Logbook ini sudah diperiksa sebelumnya.');
        }

        $logbook->update([
            'approval_status' => 'Ditolak',
            'rejection_reason' => $validated['rejection_reason'],
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('pembimbing.logbook.show', $logbook->id)
            ->with('success', 'Logbook ditolak dan alasan berhasil disimpan.');
    }

    /**
     * Ensure the logbook belongs to a peserta under this pembimbing.
     */
    private function authorizeLogbook(Logbook $logbook): void
    {
        $isOwned = User::where('id', $logbook->user_id)
            ->where('pembimbing_id', Auth::id())
            ->exists();

        if (!$isOwned) {
            abort(403, 'Anda tidak memiliki akses ke logbook ini.');
        }
    }
}
