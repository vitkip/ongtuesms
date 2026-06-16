<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfile extends Component
{
    public $full_name = '';
    public $email = '';
    public $current_password = '';
    public $new_password = '';
    public $new_password_confirmation = '';

    public $activeTab = 'info';

    public function mount()
    {
        $user = Auth::user();
        $this->full_name = $user->full_name ?? '';
        $this->email = $user->email ?? '';
    }

    public function saveInfo()
    {
        $this->validate([
            'full_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100',
        ], [
            'full_name.required' => 'ກະລຸນາປ້ອນຊື່ເຕັມ.',
            'full_name.max' => 'ຊື່ຍາວເກີນ 100 ຕົວອັກສອນ.',
            'email.email' => 'ຮູບແບບອີເມລບໍ່ຖືກຕ້ອງ.',
        ]);

        $user = Auth::user();
        $user->update([
            'full_name' => $this->full_name,
            'email' => $this->email ?: null,
        ]);

        SystemLog::create([
            'level' => 'info',
            'message' => "ຜູ້ໃຊ້ {$user->username} ແກ້ໄຂຂໍ້ມູນໂປຣໄຟລ໌",
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'update_profile'],
        ]);

        session()->flash('info_success', 'ບັນທຶກຂໍ້ມູນສ່ວນຕົວສຳເລັດແລ້ວ.');
    }

    public function savePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ], [
            'current_password.required' => 'ກະລຸນາປ້ອນລະຫັດຜ່ານປັດຈຸບັນ.',
            'new_password.required' => 'ກະລຸນາປ້ອນລະຫັດຜ່ານໃໝ່.',
            'new_password.min' => 'ລະຫັດຜ່ານໃໝ່ຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ.',
            'new_password.confirmed' => 'ການຢືນຢັນລະຫັດຜ່ານໃໝ່ບໍ່ຕົງກັນ.',
        ]);

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'ລະຫັດຜ່ານປັດຈຸບັນບໍ່ຖືກຕ້ອງ.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
        ]);

        SystemLog::create([
            'level' => 'info',
            'message' => "ຜູ້ໃຊ້ {$user->username} ປ່ຽນລະຫັດຜ່ານ",
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'change_password'],
        ]);

        $this->current_password = '';
        $this->new_password = '';
        $this->new_password_confirmation = '';
        $this->resetValidation();

        session()->flash('password_success', 'ປ່ຽນລະຫັດຜ່ານສຳເລັດແລ້ວ.');
    }

    public function render()
    {
        return view('livewire.user-profile')->layout('layouts.app');
    }
}
