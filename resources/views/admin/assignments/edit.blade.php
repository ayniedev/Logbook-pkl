@extends('layouts.admin')

@section('page-title', 'Edit Penempatan')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.assignments.index') }}">Penempatan PKL</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Edit Penempatan</h3>
            </div>
            <form action="{{ route('admin.assignments.update', $assignment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <!-- Intern (Searchable) -->
                    <div class="mb-3">
                        <label for="internship_id" class="form-label">Peserta PKL <span class="text-danger">*</span></label>
                        <div class="position-relative">
                            <input type="text" class="form-control @error('internship_id') is-invalid @enderror"
                                   id="intern-search" placeholder="Cari nama peserta..."
                                   autocomplete="off"
                                   value="{{ old('intern_name', $assignment->internship->name ?? $assignment->internship->username) }}">
                            <input type="hidden" id="internship_id" name="internship_id"
                                   value="{{ old('internship_id', $assignment->internship_id) }}">
                            <div id="intern-dropdown" class="dropdown-menu" style="width: 100%; display: none; max-height: 200px; overflow-y: auto;"></div>
                        </div>
                        @error('internship_id')
                            <div class="text-danger mt-1" style="font-size: 0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Division -->
                    <div class="mb-3">
                        <label for="division_id" class="form-label">Divisi <span class="text-danger">*</span></label>
                        <select class="form-select @error('division_id') is-invalid @enderror"
                                id="division_id" name="division_id" required>
                            <option value="">-- Pilih Divisi --</option>
                            @foreach($divisions as $div)
                                <option value="{{ $div->id }}" {{ old('division_id', $assignment->division_id) == $div->id ? 'selected' : '' }}>
                                    {{ $div->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('division_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Mentor (Filtered by Division) -->
                    <div class="mb-3">
                        <label for="mentor_id" class="form-label">Pembimbing <span class="text-danger">*</span></label>
                        <select class="form-select @error('mentor_id') is-invalid @enderror"
                                id="mentor_id" name="mentor_id" required>
                            <option value="">-- Pilih Pembimbing --</option>
                            <option value="{{ $assignment->mentor_id }}" selected>
                                {{ $assignment->mentor->name ?? $assignment->mentor->username }}
                            </option>
                        </select>
                        @error('mentor_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-1"></i> Update Penempatan
                    </button>
                    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card card-warning card-outline">
            <div class="card-header">
                <h3 class="card-title"><i class="bi bi-info-circle me-1"></i> Info Penempatan</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>Peserta</strong></td>
                        <td>{{ $assignment->internship->name ?? $assignment->internship->username }}</td>
                    </tr>
                    <tr>
                        <td><strong>Sekolah</strong></td>
                        <td>{{ $assignment->internship->schools ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Divisi</strong></td>
                        <td><span class="badge bg-info">{{ $assignment->division->name }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Pembimbing</strong></td>
                        <td>{{ $assignment->mentor->name ?? $assignment->mentor->username }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($assignment->is_active)
                                <span class="badge bg-success">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Created</strong></td>
                        <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Intern data (include current intern even if active)
    const interns = @json($interns);
    const currentInternId = {{ $assignment->internship_id }};
    const currentIntern = {
        id: {{ $assignment->internship_id }},
        name: '{{ addslashes($assignment->internship->name ?? $assignment->internship->username) }}',
        username: '{{ addslashes($assignment->internship->username) }}',
        schools: '{{ addslashes($assignment->internship->schools ?? '') }}'
    };
    // Add current intern to list if not already present
    if (!interns.find(i => i.id === currentInternId)) {
        interns.unshift(currentIntern);
    }

    const internSearch = document.getElementById('intern-search');
    const internHidden = document.getElementById('internship_id');
    const internDropdown = document.getElementById('intern-dropdown');

    internSearch.addEventListener('input', function() {
        const query = this.value.toLowerCase().trim();
        internDropdown.innerHTML = '';
        internHidden.value = '';

        if (query.length < 1) {
            internDropdown.style.display = 'none';
            return;
        }

        const filtered = interns.filter(i =>
            (i.name && i.name.toLowerCase().includes(query)) ||
            (i.username && i.username.toLowerCase().includes(query)) ||
            (i.schools && i.schools.toLowerCase().includes(query))
        );

        if (filtered.length === 0) {
            internDropdown.style.display = 'none';
            return;
        }

        filtered.forEach(intern => {
            const item = document.createElement('a');
            item.href = '#';
            item.className = 'dropdown-item';
            item.innerHTML = `<strong>${intern.name || intern.username}</strong>` +
                (intern.schools ? ` <small class="text-muted">— ${intern.schools}</small>` : '');
            item.addEventListener('click', function(e) {
                e.preventDefault();
                internSearch.value = intern.name || intern.username;
                internHidden.value = intern.id;
                internDropdown.style.display = 'none';
            });
            internDropdown.appendChild(item);
        });

        internDropdown.style.display = 'block';
    });

    document.addEventListener('click', function(e) {
        if (!internSearch.contains(e.target) && !internDropdown.contains(e.target)) {
            internDropdown.style.display = 'none';
        }
    });

    // Division -> Mentor filtering
    const divisionSelect = document.getElementById('division_id');
    const mentorSelect = document.getElementById('mentor_id');

    divisionSelect.addEventListener('change', function() {
        const divisionId = this.value;
        const currentMentorId = {{ $assignment->mentor_id }};
        mentorSelect.innerHTML = '<option value="">Memuat...</option>';
        mentorSelect.disabled = true;

        if (!divisionId) {
            mentorSelect.innerHTML = '<option value="">-- Pilih Divisi Terlebih Dahulu --</option>';
            return;
        }

        fetch('{{ route("admin.assignments.mentors-by-division") }}?division_id=' + divisionId)
            .then(response => response.json())
            .then(mentors => {
                mentorSelect.innerHTML = '<option value="">-- Pilih Pembimbing --</option>';
                mentors.forEach(mentor => {
                    const option = document.createElement('option');
                    option.value = mentor.id;
                    option.textContent = mentor.name || mentor.username;
                    if (mentor.id === currentMentorId) option.selected = true;
                    mentorSelect.appendChild(option);
                });
                mentorSelect.disabled = false;
            });
    });

    // Trigger on load
    if (divisionSelect.value) {
        divisionSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
@endsection
