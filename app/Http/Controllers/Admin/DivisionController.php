<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Http\Request;

class DivisionController extends Controller
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
     * Display a listing of divisions.
     */
    public function index(Request $request)
    {
        $query = Division::withCount(['mentors', 'internshipAssignments']);

        // Search
        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by status
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        $divisions = $query->latest()->paginate(10)->withQueryString();

        return view('admin.divisions.index', compact('divisions'));
    }

    /**
     * Show the form for creating a new division.
     */
    public function create()
    {
        return view('admin.divisions.create');
    }

    /**
     * Store a newly created division.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:divisions,name'],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Division::create($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified division.
     */
    public function edit(Division $division)
    {
        return view('admin.divisions.edit', compact('division'));
    }

    /**
     * Update the specified division.
     */
    public function update(Request $request, Division $division)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:divisions,name,' . $division->id],
            'is_active' => ['boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $division->update($validated);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil diupdate.');
    }

    /**
     * Toggle division active status.
     */
    public function toggle(Division $division)
    {
        // Check if division has active assignments
        if ($division->is_active && $division->internshipAssignments()->where('is_active', true)->exists()) {
            return redirect()->route('admin.divisions.index')
                ->with('error', 'Divisi masih memiliki penempatan aktif. Nonaktifkan penempatan terlebih dahulu.');
        }

        $division->update([
            'is_active' => !$division->is_active,
        ]);

        $status = $division->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.divisions.index')
            ->with('success', "Divisi berhasil {$status}.");
    }
}
