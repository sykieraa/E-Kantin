<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    /**
     * Display a listing of students.
     */
    public function index(Request $request): View
    {
        $students = Student::with('user')
            ->when($request->query('q'), fn ($q, $s) => $q->where(function ($w) use ($s) {
                $w->where('name', 'like', "%{$s}%")
                    ->orWhere('nis', 'like', "%{$s}%");
            }))
            ->orderBy('class_name')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new student.
     */
    public function create(): View
    {
        return view('admin.students.create');
    }

    /**
     * Store a newly created student.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'username' => $data['nis'],
                'name' => $data['name'],
                'password' => Str::random(64),
                'role' => User::ROLE_STUDENT,
                'is_active' => true,
                'must_change_password' => true,
            ]);

            $user->student()->create([
                'nis' => $data['nis'],
                'name' => $data['name'],
                'class_name' => $data['class_name'],
                'phone' => $data['phone'] ?? null,
            ]);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified student.
     */
    public function edit(Student $student): View
    {
        return view('admin.students.edit', compact('student'));
    }

    /**
     * Update the specified student.
     */
    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validateData($request, $student);

        DB::transaction(function () use ($data, $student) {
            $student->update([
                'nis' => $data['nis'],
                'name' => $data['name'],
                'class_name' => $data['class_name'],
                'phone' => $data['phone'] ?? null,
            ]);

            $student->user->update([
                'username' => $data['nis'],
                'name' => $data['name'],
            ]);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa diperbarui.');
    }

    /**
     * Generate a new activation link for the student.
     */
    public function activation(Student $student): RedirectResponse
{
    $code = strtoupper(Str::random(8));

    $student->user->update([
        'activation_token_hash' => hash('sha256', $code),
        'activation_expires_at' => now()->addHours(24),
        'must_change_password' => true,
    ]);

    return redirect()
        ->route('admin.students.index')
        ->with('activation', $this->activationPayload($student, $code))
        ->with(
            'success',
            'Kode aktivasi berhasil dibuat dan berlaku selama 24 jam.'
        );
}

    /**
     * Reset the student's password.
     *
     * The old password becomes invalid and a new activation
     * link must be generated for the student.
     */
    public function resetPassword(Student $student): RedirectResponse
{
    $code = strtoupper(Str::random(8));

    $student->user->update([
        // Password lama langsung dibuat tidak diketahui.
        'password' => Str::random(64),

        // Simpan hash kode, bukan kode aslinya.
        'activation_token_hash' => hash('sha256', $code),

        // Berlaku 24 jam.
        'activation_expires_at' => now()->addHours(24),

        'must_change_password' => true,
    ]);

    return redirect()
        ->route('admin.students.index')
        ->with('activation', $this->activationPayload($student, $code))
        ->with(
            'success',
            'Password berhasil direset. Kode aktivasi baru berlaku selama 24 jam.'
        );
}
    /**
     * Remove the specified student.
     */
    public function destroy(Student $student): RedirectResponse
    {
        $student->user->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Siswa berhasil dihapus.');
    }

    /**
    * Data sementara untuk kartu kode aktivasi admin.
    */
    private function activationPayload(Student $student, string $code): array
    {
    return [
        'student_id' => $student->id,
        'name' => $student->name,
        'nis' => $student->nis,
        'class' => $student->class_name,
        'wa_number' => $student->wa_number,
        'code' => $code,
        'activation_url' => route('student.activation'),
        ];
    }

    /**
     * Validate student data.
     */
    private function validateData(
        Request $request,
        ?Student $student = null
    ): array {
        return $request->validate([
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('students', 'nis')->ignore($student?->id),
            ],
            'name' => [
                'required',
                'string',
                'max:100',
            ],
            'class_name' => [
                'required',
                'string',
                'max:30',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^(08|62)[0-9]{8,12}$/',
            ],
        ], [
            'nis.required' => 'NIS wajib diisi.',
            'nis.unique' => 'NIS ini sudah terdaftar.',
            'name.required' => 'Nama siswa wajib diisi.',
            'class_name.required' => 'Kelas wajib diisi.',
            'phone.regex' => 'Nomor WhatsApp harus diawali 08 atau 62 dan berisi 10–14 digit.',
            'phone.max' => 'Nomor WhatsApp maksimal 20 karakter.',
        ]);
    }
}