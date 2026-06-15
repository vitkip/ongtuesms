<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Major;
use App\Models\Curriculum;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;

class AcademicManagement extends Component
{
    public $activeTab = 'majors'; // majors, curriculums, subjects

    // Search queries
    public $searchMajor = '';
    public $searchCurriculum = '';
    public $searchSubject = '';

    // Modal state
    public $showModal = false;
    public $modalType = ''; // major, curriculum, subject
    public $isEdit = false;
    public $itemId = null;

    // Major Form Fields
    public $major_name = '';
    public $major_code = '';
    public $major_description = '';
    public $major_status = 'active';

    // Curriculum Form Fields
    public $curr_major_id = '';
    public $curr_academic_year_id = '';
    public $curr_name = '';
    public $curr_code = '';
    public $curr_total_credits = 120;
    public $curr_minimum_gpa = 2.00;
    public $curr_duration_years = 4;
    public $curr_status = 'active';

    // Subject Form Fields
    public $subj_code = '';
    public $subj_name = '';
    public $subj_name_en = '';
    public $subj_credits = 3;
    public $subj_theory_hours = 32;
    public $subj_practical_hours = 0;
    public $subj_major_id = '';
    public $subj_semester = 1;
    public $subj_year_level = 1;
    public $subj_prerequisite_id = '';
    public $subj_description = '';
    public $subj_learning_outcomes = '';
    public $subj_status = 'active';

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->closeModal();
    }

    public function openCreateModal($type)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->modalType = $type;
        $this->isEdit = false;
        $this->showModal = true;
    }

    public function openEditModal($type, $id)
    {
        $this->resetValidation();
        $this->resetForm();
        $this->modalType = $type;
        $this->isEdit = true;
        $this->itemId = $id;

        if ($type === 'major') {
            $major = Major::findOrFail($id);
            $this->major_name = $major->name;
            $this->major_code = $major->code;
            $this->major_description = $major->description;
            $this->major_status = $major->status;
        } elseif ($type === 'curriculum') {
            $curr = Curriculum::findOrFail($id);
            $this->curr_major_id = $curr->major_id;
            $this->curr_academic_year_id = $curr->academic_year_id;
            $this->curr_name = $curr->curriculum_name;
            $this->curr_code = $curr->curriculum_code;
            $this->curr_total_credits = $curr->total_credits;
            $this->curr_minimum_gpa = $curr->minimum_gpa;
            $this->curr_duration_years = $curr->duration_years;
            $this->curr_status = $curr->status;
        } elseif ($type === 'subject') {
            $subj = Subject::findOrFail($id);
            $this->subj_code = $subj->subject_code;
            $this->subj_name = $subj->subject_name;
            $this->subj_name_en = $subj->subject_name_en;
            $this->subj_credits = $subj->credits;
            $this->subj_theory_hours = $subj->theory_hours;
            $this->subj_practical_hours = $subj->practical_hours;
            $this->subj_major_id = $subj->major_id;
            $this->subj_semester = $subj->semester;
            $this->subj_year_level = $subj->year_level;
            $this->subj_prerequisite_id = $subj->prerequisite_subject_id;
            $this->subj_description = $subj->description;
            $this->subj_learning_outcomes = $subj->learning_outcomes;
            $this->subj_status = $subj->status;
        }

        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->itemId = null;
        
        // Major reset
        $this->major_name = '';
        $this->major_code = '';
        $this->major_description = '';
        $this->major_status = 'active';

        // Curriculum reset
        $this->curr_major_id = '';
        $this->curr_academic_year_id = '';
        $this->curr_name = '';
        $this->curr_code = '';
        $this->curr_total_credits = 120;
        $this->curr_minimum_gpa = 2.00;
        $this->curr_duration_years = 4;
        $this->curr_status = 'active';

        // Subject reset
        $this->subj_code = '';
        $this->subj_name = '';
        $this->subj_name_en = '';
        $this->subj_credits = 3;
        $this->subj_theory_hours = 32;
        $this->subj_practical_hours = 0;
        $this->subj_major_id = '';
        $this->subj_semester = 1;
        $this->subj_year_level = 1;
        $this->subj_prerequisite_id = '';
        $this->subj_description = '';
        $this->subj_learning_outcomes = '';
        $this->subj_status = 'active';
    }

    public function saveMajor()
    {
        $this->validate([
            'major_name' => 'required|string|max:100',
            'major_code' => 'required|string|max:10',
            'major_status' => 'required|in:active,inactive',
        ], [
            'major_name.required' => 'ກະລຸນາປ້ອນຊື່ສາຂາ.',
            'major_code.required' => 'ກະລຸນາປ້ອນລະຫັດສາຂາ.',
        ]);

        if ($this->isEdit) {
            $major = Major::findOrFail($this->itemId);
            $major->update([
                'name' => $this->major_name,
                'code' => $this->major_code,
                'description' => $this->major_description,
                'status' => $this->major_status,
            ]);
            session()->flash('message', 'ແກ້ໄຂຂໍ້ມູນສາຂາວິຊາສຳເລັດແລ້ວ.');
        } else {
            Major::create([
                'name' => $this->major_name,
                'code' => $this->major_code,
                'description' => $this->major_description,
                'status' => $this->major_status,
            ]);
            session()->flash('message', 'ເພີ່ມສາຂາວິຊາໃໝ່ສຳເລັດແລ້ວ.');
        }
        $this->closeModal();
    }

    public function saveCurriculum()
    {
        $this->validate([
            'curr_name' => 'required|string|max:255',
            'curr_code' => 'required|string|max:50',
            'curr_major_id' => 'required|exists:majors,id',
            'curr_academic_year_id' => 'required|exists:academic_years,id',
            'curr_total_credits' => 'required|integer|min:1',
            'curr_status' => 'required|in:active,inactive,draft',
        ], [
            'curr_name.required' => 'ກະລຸນາປ້ອນຊື່ຫຼັກສູດ.',
            'curr_code.required' => 'ກະລຸນາປ້ອນລະຫັດຫຼັກສູດ.',
            'curr_major_id.required' => 'ກະລຸນາເລືອກສາຂາ.',
            'curr_academic_year_id.required' => 'ກະລຸນາເລືອກສົກຮຽນ.',
            'curr_total_credits.required' => 'ກະລຸນາປ້ອນໜ່ວຍກິດລວມ.',
        ]);

        if ($this->isEdit) {
            $curr = Curriculum::findOrFail($this->itemId);
            $curr->update([
                'major_id' => $this->curr_major_id,
                'academic_year_id' => $this->curr_academic_year_id,
                'curriculum_name' => $this->curr_name,
                'curriculum_code' => $this->curr_code,
                'total_credits' => $this->curr_total_credits,
                'minimum_gpa' => $this->curr_minimum_gpa,
                'duration_years' => $this->curr_duration_years,
                'status' => $this->curr_status,
            ]);
            session()->flash('message', 'ແກ້ໄຂຂໍ້ມູນຫຼັກສູດສຳເລັດແລ້ວ.');
        } else {
            Curriculum::create([
                'major_id' => $this->curr_major_id,
                'academic_year_id' => $this->curr_academic_year_id,
                'curriculum_name' => $this->curr_name,
                'curriculum_code' => $this->curr_code,
                'total_credits' => $this->curr_total_credits,
                'minimum_gpa' => $this->curr_minimum_gpa,
                'duration_years' => $this->curr_duration_years,
                'status' => $this->curr_status,
            ]);
            session()->flash('message', 'ເພີ່ມຫຼັກສູດໃໝ່ສຳເລັດແລ້ວ.');
        }
        $this->closeModal();
    }

    public function saveSubject()
    {
        $this->validate([
            'subj_code' => 'required|string|max:20',
            'subj_name' => 'required|string|max:255',
            'subj_credits' => 'required|integer|min:1',
            'subj_major_id' => 'required|exists:majors,id',
            'subj_semester' => 'required|integer|between:1,8',
            'subj_year_level' => 'required|integer|between:1,4',
            'subj_status' => 'required|in:active,inactive',
        ], [
            'subj_code.required' => 'ກະລຸນາປ້ອນລະຫັດວິຊາ.',
            'subj_name.required' => 'ກະລຸນາປ້ອນຊື່ວິຊາ (ພາສາລາວ).',
            'subj_credits.required' => 'ກະລຸນາປ້ອນໜ່ວຍກິດ.',
            'subj_major_id.required' => 'ກະລຸນາເລືອກສາຂາ.',
        ]);

        $prereqId = empty($this->subj_prerequisite_id) ? null : $this->subj_prerequisite_id;

        if ($this->isEdit) {
            $subj = Subject::findOrFail($this->itemId);
            $subj->update([
                'subject_code' => $this->subj_code,
                'subject_name' => $this->subj_name,
                'subject_name_en' => $this->subj_name_en,
                'credits' => $this->subj_credits,
                'theory_hours' => $this->subj_theory_hours,
                'practical_hours' => $this->subj_practical_hours,
                'major_id' => $this->subj_major_id,
                'semester' => $this->subj_semester,
                'year_level' => $this->subj_year_level,
                'prerequisite_subject_id' => $prereqId,
                'description' => $this->subj_description,
                'learning_outcomes' => $this->subj_learning_outcomes,
                'status' => $this->subj_status,
            ]);
            session()->flash('message', 'ແກ້ໄຂຂໍ້ມູນວິຊາຮຽນສຳເລັດແລ້ວ.');
        } else {
            Subject::create([
                'subject_code' => $this->subj_code,
                'subject_name' => $this->subj_name,
                'subject_name_en' => $this->subj_name_en,
                'credits' => $this->subj_credits,
                'theory_hours' => $this->subj_theory_hours,
                'practical_hours' => $this->subj_practical_hours,
                'major_id' => $this->subj_major_id,
                'semester' => $this->subj_semester,
                'year_level' => $this->subj_year_level,
                'prerequisite_subject_id' => $prereqId,
                'description' => $this->subj_description,
                'learning_outcomes' => $this->subj_learning_outcomes,
                'status' => $this->subj_status,
            ]);
            session()->flash('message', 'ເພີ່ມວິຊາຮຽນໃໝ່ສຳເລັດແລ້ວ.');
        }
        $this->closeModal();
    }

    public function deleteItem($type, $id)
    {
        if ($type === 'major') {
            $major = Major::findOrFail($id);
            $major->delete();
            session()->flash('message', 'ລຶບສາຂາວິຊາສຳເລັດແລ້ວ.');
        } elseif ($type === 'curriculum') {
            $curr = Curriculum::findOrFail($id);
            $curr->delete();
            session()->flash('message', 'ລຶບຫຼັກສູດສຳເລັດແລ້ວ.');
        } elseif ($type === 'subject') {
            $subj = Subject::findOrFail($id);
            $subj->delete();
            session()->flash('message', 'ລຶບວິຊາຮຽນສຳເລັດແລ້ວ.');
        }
    }

    public function render()
    {
        // Fetch data based on active tab
        $majorsList = [];
        $curriculumsList = [];
        $subjectsList = [];

        if ($this->activeTab === 'majors') {
            $majorsList = Major::query()
                ->when($this->searchMajor, function ($q) {
                    $q->where('name', 'LIKE', '%' . $this->searchMajor . '%')
                      ->orWhere('code', 'LIKE', '%' . $this->searchMajor . '%');
                })
                ->orderBy('name', 'asc')
                ->get();
        } elseif ($this->activeTab === 'curriculums') {
            $curriculumsList = Curriculum::query()
                ->with(['major', 'academicYear'])
                ->when($this->searchCurriculum, function ($q) {
                    $q->where('curriculum_name', 'LIKE', '%' . $this->searchCurriculum . '%')
                      ->orWhere('curriculum_code', 'LIKE', '%' . $this->searchCurriculum . '%');
                })
                ->orderBy('curriculum_code', 'asc')
                ->get();
        } elseif ($this->activeTab === 'subjects') {
            $subjectsList = Subject::query()
                ->with(['major', 'prerequisite'])
                ->when($this->searchSubject, function ($q) {
                    $q->where('subject_name', 'LIKE', '%' . $this->searchSubject . '%')
                      ->orWhere('subject_code', 'LIKE', '%' . $this->searchSubject . '%');
                })
                ->orderBy('subject_code', 'asc')
                ->get();
        }

        $allMajors = Major::all();
        $allYears = AcademicYear::all();
        $allSubjects = Subject::all();

        return view('livewire.academic-management', [
            'majorsList' => $majorsList,
            'curriculumsList' => $curriculumsList,
            'subjectsList' => $subjectsList,
            'allMajors' => $allMajors,
            'allYears' => $allYears,
            'allSubjects' => $allSubjects,
        ])->layout('layouts.app');
    }
}
