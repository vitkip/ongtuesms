<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Subject;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    // Modal state
    public $showModal = false;
    public $showDeleteModal = false;
    public $editingId = null;
    public $deleteId = null;

    // Form fields
    public $username = '';
    public $password = '';
    public $password_confirmation = '';
    public $full_name = '';
    public $email = '';
    public $role = 'user';
    public $is_active = true;
    public $selectedSubjects = [];

    protected $paginationTheme = 'tailwind';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->resetForm();
        $this->editingId = null;
        $this->showModal = true;
    }

    public function openEdit($id)
    {
        $user = User::with('subjects')->findOrFail($id);
        $this->editingId = $id;
        $this->username = $user->username;
        $this->full_name = $user->full_name ?? '';
        $this->email = $user->email ?? '';
        $this->role = $user->role;
        $this->is_active = $user->is_active;
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedSubjects = $user->subjects->pluck('id')->map(fn($v) => (string) $v)->toArray();
        $this->showModal = true;
        $this->resetValidation();
    }

    public function save()
    {
        $isCreating = is_null($this->editingId);

        $rules = [
            'username' => 'required|string|max:50|unique:users,username' . ($isCreating ? '' : ',' . $this->editingId),
            'full_name' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'role' => 'required|in:admin,user,finance',
            'is_active' => 'boolean',
            'selectedSubjects' => 'array',
            'selectedSubjects.*' => 'exists:subjects,id',
        ];

        if ($isCreating) {
            $rules['password'] = 'required|string|min:6|confirmed';
        } else {
            $rules['password'] = 'nullable|string|min:6|confirmed';
        }

        $messages = [
            'username.required' => 'ກະລຸນາປ້ອນຊື່ຜູ້ໃຊ້.',
            'username.unique' => 'ຊື່ຜູ້ໃຊ້ນີ້ຖືກໃຊ້ແລ້ວ.',
            'username.max' => 'ຊື່ຜູ້ໃຊ້ຍາວເກີນ 50 ຕົວອັກສອນ.',
            'password.required' => 'ກະລຸນາປ້ອນລະຫັດຜ່ານ.',
            'password.min' => 'ລະຫັດຜ່ານຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ.',
            'password.confirmed' => 'ລະຫັດຜ່ານຢືນຢັນບໍ່ຕົງກັນ.',
            'email.email' => 'ຮູບແບບອີເມລບໍ່ຖືກຕ້ອງ.',
            'role.required' => 'ກະລຸນາເລືອກສິດໃຊ້ງານ.',
        ];

        $this->validate($rules, $messages);

        if ($isCreating) {
            $user = User::create([
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'full_name' => $this->full_name ?: null,
                'email' => $this->email ?: null,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ]);

            $user->subjects()->sync($this->selectedSubjects);

            SystemLog::create([
                'level' => 'info',
                'message' => "ສ້າງຜູ້ໃຊ້ໃໝ່: {$user->username} ({$user->full_name})",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'create_user', 'new_user_id' => $user->id],
            ]);

            session()->flash('message', 'ເພີ່ມຜູ້ໃຊ້ສຳເລັດແລ້ວ.');
        } else {
            $user = User::findOrFail($this->editingId);

            $data = [
                'username' => $this->username,
                'full_name' => $this->full_name ?: null,
                'email' => $this->email ?: null,
                'role' => $this->role,
                'is_active' => $this->is_active,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);
            $user->subjects()->sync($this->selectedSubjects);

            SystemLog::create([
                'level' => 'info',
                'message' => "ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້: {$user->username} ({$user->full_name})",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'update_user', 'target_user_id' => $user->id],
            ]);

            session()->flash('message', 'ແກ້ໄຂຂໍ້ມູນຜູ້ໃຊ້ສຳເລັດແລ້ວ.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete($id)
    {
        if ($id === Auth::id()) {
            session()->flash('error', 'ບໍ່ສາມາດລົບບັນຊີຂອງຕົນເອງໄດ້.');
            return;
        }
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteUser()
    {
        if ($this->deleteId === Auth::id()) {
            session()->flash('error', 'ບໍ່ສາມາດລົບບັນຊີຂອງຕົນເອງໄດ້.');
            $this->showDeleteModal = false;
            return;
        }

        $user = User::findOrFail($this->deleteId);
        $user->subjects()->detach();

        SystemLog::create([
            'level' => 'warning',
            'message' => "ລົບຜູ້ໃຊ້: {$user->username} ({$user->full_name})",
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'delete_user', 'deleted_user_id' => $user->id],
        ]);

        $user->delete();

        session()->flash('message', 'ລົບຜູ້ໃຊ້ສຳເລັດແລ້ວ.');
        $this->showDeleteModal = false;
        $this->deleteId = null;
    }

    public function toggleActive($id)
    {
        if ($id === Auth::id()) {
            session()->flash('error', 'ບໍ່ສາມາດລະງັບບັນຊີຂອງຕົນເອງໄດ້.');
            return;
        }

        $user = User::findOrFail($id);
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ເປີດໃຊ້ງານ' : 'ລະງັບ';
        $name = $user->full_name ?: $user->username;
        session()->flash('message', "{$status}ບັນຊີ {$name} ສຳເລັດ.");
    }

    private function resetForm()
    {
        $this->username = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->full_name = '';
        $this->email = '';
        $this->role = 'user';
        $this->is_active = true;
        $this->selectedSubjects = [];
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()
            ->with('subjects')
            ->when($this->search, fn($q) =>
                $q->where('username', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('full_name', 'LIKE', '%' . $this->search . '%')
                  ->orWhere('email', 'LIKE', '%' . $this->search . '%')
            )
            ->orderBy('full_name')
            ->paginate(10);

        $subjects = Subject::where('status', 'active')
            ->with('major')
            ->orderBy('subject_name')
            ->get();

        return view('livewire.user-management', [
            'users' => $users,
            'subjects' => $subjects,
        ])->layout('layouts.app');
    }
}
