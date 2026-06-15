<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Setting;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsManagement extends Component
{
    use WithFileUploads;

    // Settings fields
    public $school_name = '';
    public $school_name_en = '';
    public $school_address = '';
    public $school_phone = '';
    public $school_email = '';
    public $bank_account_number = '';
    public $enable_email_notifications = '0';
    
    // Logo upload fields
    public $logo;
    public $existing_logo;

    // Bank QR Code upload fields
    public $bank_qr_code;
    public $existing_bank_qr_code;

    protected function rules()
    {
        return [
            'school_name' => 'required|string|max:255',
            'school_name_en' => 'nullable|string|max:255',
            'school_address' => 'required|string|max:255',
            'school_phone' => 'nullable|string|max:50',
            'school_email' => 'nullable|email|max:100',
            'bank_account_number' => 'required|string|max:50',
            'enable_email_notifications' => 'required|in:0,1',
            'logo' => 'nullable|image|max:2048',
            'bank_qr_code' => 'nullable|image|max:2048',
        ];
    }

    protected $messages = [
        'school_name.required' => 'ກະລຸນາປ້ອນຊື່ວິທະຍາໄລ.',
        'school_address.required' => 'ກະລຸນາປ້ອນທີ່ຢູ່.',
        'bank_account_number.required' => 'ກະລຸນາປ້ອນເລກບັນຊີທະນາຄານ.',
        'school_email.email' => 'ຮູບແບບອີເມລບໍ່ຖືກຕ້ອງ.',
        'logo.image' => 'ໂລໂກ້ຕ້ອງເປັນໄຟລ໌ຮູບພາບ (PNG, JPG, JPEG, etc.).',
        'logo.max' => 'ໂລໂກ້ຕ້ອງມີຂະໜາດບໍ່ເກີນ 2MB.',
        'bank_qr_code.image' => 'QR Code ຕ້ອງເປັນໄຟລ໌ຮູບພາບ (PNG, JPG, JPEG, etc.).',
        'bank_qr_code.max' => 'QR Code ຕ້ອງມີຂະໜາດບໍ່ເກີນ 2MB.',
    ];

    public function mount()
    {
        $this->school_name = setting('school_name', 'ວິທະຍາໄລຄູສົງ ອົງຕື້');
        $this->school_name_en = setting('school_name_en', 'Ongtue Sangha Teacher Training College');
        $this->school_address = setting('school_address', 'ບ້ານວັດຈັນ, ເມືອງຈັນທະບູລີ, ນະຄອນຫຼວງວຽງຈັນ');
        $this->school_phone = setting('school_phone', '021 215 038');
        $this->school_email = setting('school_email', 'info@ongtue.edu.la');
        $this->bank_account_number = setting('bank_account_number', '01452026000028');
        $this->enable_email_notifications = setting('enable_email_notifications', '0');
        $this->existing_logo = setting('school_logo');
        $this->existing_bank_qr_code = setting('bank_qr_code');
    }

    public function saveSettings()
    {
        $this->validate();

        if ($this->logo) {
            $logoName = 'logo_' . time() . '.' . $this->logo->getClientOriginalExtension();
            $this->logo->storeAs('settings', $logoName, 'public');
            
            // Delete old logo file if it exists and is not the default
            $oldLogo = setting('school_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            
            Setting::setVal('school_logo', 'settings/' . $logoName);
            $this->existing_logo = 'settings/' . $logoName;
            $this->logo = null;
        }

        if ($this->bank_qr_code) {
            $qrName = 'bank_qr_' . time() . '.' . $this->bank_qr_code->getClientOriginalExtension();
            $this->bank_qr_code->storeAs('settings', $qrName, 'public');

            $oldQr = setting('bank_qr_code');
            if ($oldQr && Storage::disk('public')->exists($oldQr)) {
                Storage::disk('public')->delete($oldQr);
            }

            Setting::setVal('bank_qr_code', 'settings/' . $qrName);
            $this->existing_bank_qr_code = 'settings/' . $qrName;
            $this->bank_qr_code = null;
        }

        Setting::setVal('school_name', $this->school_name);
        Setting::setVal('school_name_en', $this->school_name_en);
        Setting::setVal('school_address', $this->school_address);
        Setting::setVal('school_phone', $this->school_phone);
        Setting::setVal('school_email', $this->school_email);
        Setting::setVal('bank_account_number', $this->bank_account_number);
        Setting::setVal('enable_email_notifications', $this->enable_email_notifications);

        SystemLog::create([
            'level' => 'warning',
            'message' => 'ຜູ້ບໍລິຫານລະບົບໄດ້ທຳການອັບເດດການຕັ້ງຄ່າລະບົບ (System Settings)',
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'update_settings']
        ]);

        session()->flash('message', 'ບັນທຶກການຕັ້ງຄ່າລະບົບຮຽບຮ້ອຍແລ້ວ.');
    }

    public function render()
    {
        return view('livewire.settings-management')->layout('layouts.app');
    }
}
