<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

class MentorController extends Controller
{
    /**
     * Constructor - restrict access to Admin only.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $user = $request->user();
            $roleName = optional($user)->role?->name;

            if ($roleName !== 'Admin') {
                abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
            }

            return $next($request);
        });
    }

    /**
     * Display a listing of mentors (pembimbing).
     */
    public function index(Request $request)
    {
        $query = User::whereHas('role', function ($q) {
            $q->where('name', 'Pembimbing');
        })->with('division');

        // Search
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by division
        if ($divisionId = $request->input('division_id')) {
            $query->where('division_id', $divisionId);
        }

        // Filter by status
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        $mentors = $query->latest()->paginate(10)->withQueryString();
        $divisions = Division::where('is_active', true)->orderBy('name')->get();

        return view('admin.mentors.index', compact('mentors', 'divisions'));
    }

    /**
     * Show the form for editing the specified mentor.
     */
    public function edit(User $mentor)
    {
        // Ensure this is a Pembimbing
        if (optional($mentor->role)->name !== 'Pembimbing') {
            return redirect()->route('admin.mentors.index')
                ->with('error', 'User ini bukan pembimbing.');
        }

        $divisions = Division::where('is_active', true)->orderBy('name')->get();

        return view('admin.mentors.edit', compact('mentor', 'divisions'));
    }

    /**
     * Update the specified mentor's division.
     */
    public function update(Request $request, User $mentor)
    {
        // Ensure this is a Pembimbing
        if (optional($mentor->role)->name !== 'Pembimbing') {
            return redirect()->route('admin.mentors.index')
                ->with('error', 'User ini bukan pembimbing.');
        }

        $validated = $request->validate([
            'division_id' => ['nullable', 'exists:divisions,id'],
        ]);

        $mentor->update([
            'division_id' => $validated['division_id'] ?? null,
        ]);

        return redirect()->route('admin.mentors.index')
            ->with('success', 'Divisi pembimbing berhasil diupdate.');
    }

    /**
     * Get mentors by division (for AJAX).
     */
    public function getByDivision(Request $request)
    {
        $divisionId = $request->input('division_id');

        if (!$divisionId) {
            return response()->json([]);
        }

        $mentors = User::whereHas('role', function ($q) {
            $q->where('name', 'Pembimbing');
        })
        ->where('division_id', $divisionId)
        ->where('is_active', true)
        ->orderBy('name')
        ->get(['id', 'name', 'username']);

        return response()->json($mentors);
    }
}
