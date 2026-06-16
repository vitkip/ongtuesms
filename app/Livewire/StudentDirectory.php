<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class StudentDirectory extends Component
{
    use WithPagination, WithFileUploads;

    // Search and Filters
    public $search = '';
    public $filterMajor = '';
    public $filterYear = '';
    public $filterGender = '';
    public $filterYearLevel = '';

    // Modal state
    public $showModal = false;
    public $isEdit = false;
    public $studentId = null; // PK id

    // Form fields
    public $first_name = '';
    public $last_name = '';
    public $gender = 'ພຣະ';
    public $dob = '';
    public $email = '';
    public $phone = '';
    public $village = '';
    public $district = '';
    public $province = '';
    public $accommodation_type = 'ມີວັດຢູ່ແລ້ວ';
    public $major_id = '';
    public $academic_year_id = '';
    public $previous_school = '';
    public $photo = null;
    public $existingPhoto = null;

    protected $paginationTheme = 'tailwind';

    protected function rules()
    {
        return [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'gender' => 'required|in:ພຣະ,ສ.ນ,ຊາຍ,ຍິງ,ອຶ່ນໆ',
            'dob' => 'required|date',
            'email' => 'nullable|email|max:100',
            'phone' => 'nullable|string|max:20',
            'village' => 'nullable|string|max:100',
            'district' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'accommodation_type' => 'nullable|in:ຫາວັດໃຫ້,ມີວັດຢູ່ແລ້ວ',
            'major_id' => 'required|exists:majors,id',
            'academic_year_id' => 'required|exists:academic_years,id',
            'previous_school' => 'nullable|string|max:255',
            'photo' => 'nullable|image|max:5120', // max 5MB
        ];
    }

    protected $messages = [
        'first_name.required' => 'ກະລຸນາປ້ອນຊື່.',
        'last_name.required' => 'ກະລຸນາປ້ອນນາມສະກຸນ.',
        'gender.required' => 'ກະລຸນາເລືອກເພດ.',
        'dob.required' => 'ກະລຸນາປ້ອນວັນເກີດ.',
        'major_id.required' => 'ກະລຸນາເລືອກສາຂາວິຊາ.',
        'academic_year_id.required' => 'ກະລຸນາເລືອກປີການສຶກສາ.',
        'email.email' => 'ຮູບແບບອີເມລບໍ່ຖືກຕ້ອງ.',
        'photo.max' => 'ຂະໜາດຮູບພາບຕ້ອງບໍ່ເກີນ 5MB.',
        'photo.image' => 'ໄຟລ໌ຕ້ອງເປັນຮູບພາບເທົ່ານັ້ນ.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterMajor = '';
        $this->filterYear = '';
        $this->filterGender = '';
        $this->filterYearLevel = '';
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEdit = true;
        
        $student = Student::findOrFail($id);
        $this->studentId = $student->id;
        $this->first_name = $student->first_name;
        $this->last_name = $student->last_name;
        $this->gender = $student->gender;
        $this->dob = $student->dob ? $student->dob->format('Y-m-d') : '';
        $this->email = $student->email;
        $this->phone = $student->phone;
        $this->village = $student->village;
        $this->district = $student->district;
        $this->province = $student->province;
        $this->accommodation_type = $student->accommodation_type ?: 'ມີວັດຢູ່ແລ້ວ';
        $this->major_id = $student->major_id;
        $this->academic_year_id = $student->academic_year_id;
        $this->previous_school = $student->previous_school;
        $this->existingPhoto = $student->photo;

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->studentId = null;
        $this->first_name = '';
        $this->last_name = '';
        $this->gender = 'ພຣະ';
        $this->dob = '';
        $this->email = '';
        $this->phone = '';
        $this->village = '';
        $this->district = '';
        $this->province = '';
        $this->accommodation_type = 'ມີວັດຢູ່ແລ້ວ';
        $this->major_id = '';
        $this->academic_year_id = '';
        $this->previous_school = '';
        $this->photo = null;
        $this->existingPhoto = null;
    }

    private function generateStudentId($academicYearId)
    {
        $yearModel = AcademicYear::find($academicYearId);
        if (!$yearModel) return null;
        
        // Extract first 4 digits of the year (e.g., 2025 from 2025-2026)
        $yearParts = explode('-', $yearModel->year);
        $yearStr = $yearParts[0] ?? date('Y');

        $prefix = "0145" . $yearStr;
        
        // Find maximum sequence starting with this prefix
        $lastStudent = Student::where('student_id', 'LIKE', "{$prefix}%")
            ->orderBy('student_id', 'desc')
            ->first();

        if ($lastStudent) {
            $lastSeq = substr($lastStudent->student_id, strlen($prefix));
            $nextSeqInt = intval($lastSeq) + 1;
        } else {
            $nextSeqInt = 1;
        }

        $nextSeqStr = str_pad($nextSeqInt, 6, '0', STR_PAD_LEFT);
        return $prefix . $nextSeqStr;
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Handle Photo upload
        if ($this->photo) {
            $photoName = time() . '_' . $this->photo->getClientOriginalName();
            $this->photo->storeAs('students', $photoName, 'public');
            $validatedData['photo'] = $photoName;
        } else {
            unset($validatedData['photo']);
        }

        if ($this->isEdit) {
            $student = Student::findOrFail($this->studentId);
            
            // Delete old photo if new one uploaded
            if ($this->photo && $student->photo) {
                Storage::disk('public')->delete('students/' . $student->photo);
            }

            $student->update($validatedData);

            SystemLog::create([
                'level' => 'info',
                'message' => "ແກ້ໄຂຂໍ້ມູນນັກສຶກສາ: {$student->full_name} ({$student->student_id})",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'update_student', 'student_id' => $student->student_id]
            ]);

            session()->flash('message', 'ແກ້ໄຂຂໍ້ມູນນັກສຶກສາສຳເລັດແລ້ວ.');
        } else {
            // Auto generate student_id
            $validatedData['student_id'] = $this->generateStudentId($this->academic_year_id);
            $validatedData['registered_at'] = now();
            
            $student = Student::create($validatedData);

            SystemLog::create([
                'level' => 'info',
                'message' => "ເພີ່ມນັກສຶກສາໃໝ່: {$student->full_name} ({$student->student_id})",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'create_student', 'student_id' => $student->student_id]
            ]);

            session()->flash('message', 'ເພີ່ມນັກສຶກສາໃໝ່ສຳເລັດແລ້ວ.');
        }

        $this->closeModal();
    }

    public function delete($id)
    {
        $student = Student::findOrFail($id);
        $studentName = $student->full_name;
        $studentIdStr = $student->student_id;

        // Delete photo if exists
        if ($student->photo) {
            Storage::disk('public')->delete('students/' . $student->photo);
        }

        $student->delete();

        SystemLog::create([
            'level' => 'warning',
            'message' => "ລຶບຂໍ້ມູນນັກສຶກສາ: {$studentName} ({$studentIdStr})",
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'delete_student', 'student_id' => $studentIdStr]
        ]);

        session()->flash('message', 'ລຶບຂໍ້ມູນນັກສຶກສາສຳເລັດແລ້ວ.');
    }

    public function render()
    {
        $query = Student::query()->with(['major', 'academicYear']);

        // Search
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('first_name', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('student_id', 'LIKE', '%' . $this->search . '%');
            });
        }

        // Filters
        if ($this->filterMajor) {
            $query->where('major_id', $this->filterMajor);
        }
        if ($this->filterYear) {
            $query->where('academic_year_id', $this->filterYear);
        }
        if ($this->filterGender) {
            $query->where('gender', $this->filterGender);
        }
        if ($this->filterYearLevel) {
            // Convert requested year_level to the expected enrollment start year
            // year_level N in current cycle → enrolled in (currentStart - N + 1)
            $now = now();
            $currentStart = $now->month >= 6 ? $now->year : $now->year - 1;
            $enrollStart  = $currentStart - (int)$this->filterYearLevel + 1;
            $query->whereHas('academicYear', fn($q) =>
                $q->where('year', 'LIKE', $enrollStart . '%')
            );
        }

        $students = $query->orderBy('student_id', 'asc')->paginate(10);
        
        $majors = Major::all();
        $academicYears = AcademicYear::all();

        return view('livewire.student-directory', [
            'students' => $students,
            'majors' => $majors,
            'academicYears' => $academicYears,
        ])->layout('layouts.app');
    }
}
