<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\InternshipAssignment;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AssignmentController extends Controller
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
     * Display a listing of assignments.
     */
    public function index(Request $request)
    {
        $query = InternshipAssignment::with(['internship', 'division', 'mentor']);

        // Search by intern name
        if ($search = $request->input('search')) {
            $query->whereHas('internship', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }

        // Filter by division
        if ($divisionId = $request->input('division_id')) {
            $query->where('division_id', $divisionId);
        }

        // Filter by mentor
        if ($mentorId = $request->input('mentor_id')) {
            $query->where('mentor_id', $mentorId);
        }

        // Filter by status
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', $request->input('is_active') === '1');
        }

        $assignments = $query->latest()->paginate(10)->withQueryString();
        $divisions = Division::where('is_active', true)->orderBy('name')->get();
        $mentors = User::whereHas('role', function ($q) {
            $q->where('name', 'Pembimbing');
        })->where('is_active', true)->orderBy('name')->get();

        return view('admin.assignments.index', compact('assignments', 'divisions', 'mentors'));
    }

    /**
     * Show the form for creating a new assignment.
     */
    public function create()
    {
        // Get interns without active assignments
        $internRoleId = Role::where('name', 'Internship')->first()?->id;
        $assignedInternIds = InternshipAssignment::where('is_active', true)
            ->pluck('internship_id')
            ->toArray();

        $interns = User::where('role_id', $internRoleId)
            ->where('is_active', true)
            ->whereNotIn('id', $assignedInternIds)
            ->orderBy('name')
            ->get();

        $divisions = Division::where('is_active', true)->orderBy('name')->get();

        return view('admin.assignments.create', compact('interns', 'divisions'));
    }

    /**
     * Store a newly created assignment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'internship_id' => ['required', 'exists:users,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'mentor_id' => ['required', 'exists:users,id'],
        ], [
            'internship_id.required' => 'Peserta PKL wajib dipilih.',
            'internship_id.exists' => 'Peserta PKL tidak valid.',
            'division_id.required' => 'Divisi wajib dipilih.',
            'division_id.exists' => 'Divisi tidak valid.',
            'mentor_id.required' => 'Pembimbing wajib dipilih.',
            'mentor_id.exists' => 'Pembimbing tidak valid.',
        ]);

        // Validate intern role
        $intern = User::find($validated['internship_id']);
        if (optional($intern->role)->name !== 'Internship') {
            return back()->withErrors(['internship_id' => 'User yang dipilih bukan peserta PKL.'])->withInput();
        }

        // Validate mentor role
        $mentor = User::find($validated['mentor_id']);
        if (optional($mentor->role)->name !== 'Pembimbing') {
            return back()->withErrors(['mentor_id' => 'User yang dipilih bukan pembimbing.'])->withInput();
        }

        // Validate mentor belongs to selected division
        if ($mentor->division_id != $validated['division_id']) {
            return back()->withErrors(['mentor_id' => 'Pembimbing tidak berada di divisi yang dipilih.'])->withInput();
        }

        // Check if intern already has an active assignment
        $existingAssignment = InternshipAssignment::where('internship_id', $validated['internship_id'])
            ->where('is_active', true)
            ->exists();

        if ($existingAssignment) {
            return back()->withErrors(['internship_id' => 'Peserta PKL sudah memiliki penempatan aktif.'])->withInput();
        }

        // Validate division is active
        $division = Division::find($validated['division_id']);
        if (!$division->is_active) {
            return back()->withErrors(['division_id' => 'Divisi yang dipilih tidak aktif.'])->withInput();
        }

        InternshipAssignment::create($validated);

        // Update pembimbing_id on the intern's user record
        $intern->update(['pembimbing_id' => $validated['mentor_id']]);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Penempatan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified assignment.
     */
    public function edit(InternshipAssignment $assignment)
    {
        // Get all interns (including current one)
        $internRoleId = Role::where('name', 'Internship')->first()?->id;
        $assignedInternIds = InternshipAssignment::where('is_active', true)
            ->where('id', '!=', $assignment->id)
            ->pluck('internship_id')
            ->toArray();

        $interns = User::where('role_id', $internRoleId)
            ->where('is_active', true)
            ->whereNotIn('id', $assignedInternIds)
            ->orderBy('name')
            ->get();

        $divisions = Division::where('is_active', true)->orderBy('name')->get();

        return view('admin.assignments.edit', compact('assignment', 'interns', 'divisions'));
    }

    /**
     * Update the specified assignment.
     */
    public function update(Request $request, InternshipAssignment $assignment)
    {
        $validated = $request->validate([
            'internship_id' => ['required', 'exists:users,id'],
            'division_id' => ['required', 'exists:divisions,id'],
            'mentor_id' => ['required', 'exists:users,id'],
        ]);

        // Validate intern role
        $intern = User::find($validated['internship_id']);
        if (optional($intern->role)->name !== 'Internship') {
            return back()->withErrors(['internship_id' => 'User yang dipilih bukan peserta PKL.'])->withInput();
        }

        // Validate mentor role
        $mentor = User::find($validated['mentor_id']);
        if (optional($mentor->role)->name !== 'Pembimbing') {
            return back()->withErrors(['mentor_id' => 'User yang dipilih bukan pembimbing.'])->withInput();
        }

        // Validate mentor belongs to selected division
        if ($mentor->division_id != $validated['division_id']) {
            return back()->withErrors(['mentor_id' => 'Pembimbing tidak berada di divisi yang dipilih.'])->withInput();
        }

        // Check if intern already has an active assignment (excluding current)
        $existingAssignment = InternshipAssignment::where('internship_id', $validated['internship_id'])
            ->where('is_active', true)
            ->where('id', '!=', $assignment->id)
            ->exists();

        if ($existingAssignment) {
            return back()->withErrors(['internship_id' => 'Peserta PKL sudah memiliki penempatan aktif.'])->withInput();
        }

        // Validate division is active
        $division = Division::find($validated['division_id']);
        if (!$division->is_active) {
            return back()->withErrors(['division_id' => 'Divisi yang dipilih tidak aktif.'])->withInput();
        }

        $assignment->update($validated);

        // Update pembimbing_id on the intern's user record
        $intern->update(['pembimbing_id' => $validated['mentor_id']]);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Penempatan berhasil diupdate.');
    }

    /**
     * Toggle assignment active status.
     */
    public function toggle(InternshipAssignment $assignment)
    {
        $assignment->update([
            'is_active' => !$assignment->is_active,
        ]);

        // If deactivating, clear pembimbing_id on the intern
        if (!$assignment->is_active) {
            User::where('id', $assignment->internship_id)
                ->update(['pembimbing_id' => null]);
        } else {
            // If activating, set pembimbing_id on the intern
            User::where('id', $assignment->internship_id)
                ->update(['pembimbing_id' => $assignment->mentor_id]);
        }

        $status = $assignment->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('admin.assignments.index')
            ->with('success', "Penempatan berhasil {$status}.");
    }

    /**
     * Get mentors by division (for AJAX).
     */
    public function getMentorsByDivision(Request $request)
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
