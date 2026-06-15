<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Student;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceManagement extends Component
{
    use WithPagination;

    // Filters
    public $searchInvoice = '';
    public $filterStatus = '';
    public $studentSearch = '';

    // Modal state
    public $showCreateModal = false;
    public $showPaymentModal = false;

    // Form fields (Create)
    public $student_id = '';
    public $card_fee = 20000;
    public $photo_fee = 20000;
    public $email_fee = 50000;
    public $tuition_fee = 0;
    public $bank_account_number = '01452026000028';
    public $notes = '';

    // Form fields (Payment Update)
    public $selectedInvoiceId = null;
    public $payment_status = 'paid';
    public $payment_date = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->payment_date = date('Y-m-d');
    }

    private function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Y');
        
        $lastInvoice = Invoice::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            $lastSeq = substr($lastInvoice->invoice_number, strlen($prefix) + 1);
            $nextSeq = intval($lastSeq) + 1;
        } else {
            $nextSeq = 1;
        }

        return $prefix . '-' . str_pad($nextSeq, 5, '0', STR_PAD_LEFT);
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->student_id = '';
        $this->studentSearch = '';
        $this->card_fee = 20000;
        $this->photo_fee = 20000;
        $this->email_fee = 50000;
        $this->tuition_fee = 0;
        $this->notes = '';
        $this->showCreateModal = true;
    }

    public function createInvoice()
    {
        $this->validate([
            'student_id' => 'required|exists:students,id',
            'card_fee' => 'required|numeric|min:0',
            'photo_fee' => 'required|numeric|min:0',
            'email_fee' => 'required|numeric|min:0',
            'tuition_fee' => 'required|numeric|min:0',
            'bank_account_number' => 'required|string|max:50',
        ], [
            'student_id.required' => 'ກະລຸນາເລືອກນັກສຶກສາ.',
            'card_fee.required' => 'ກະລຸນາປ້ອນຄ່າບັດ.',
            'photo_fee.required' => 'ກະລຸນາປ້ອນຄ່າຮູບ.',
            'email_fee.required' => 'ກະລຸນາປ້ອນຄ່າອີເມລ.',
            'tuition_fee.required' => 'ກະລຸນາປ້ອນຄ່າຮຽນ.',
        ]);

        $student = Student::findOrFail($this->student_id);
        $invoiceNumber = $this->generateInvoiceNumber();
        $total = floatval($this->card_fee) + floatval($this->photo_fee) + floatval($this->email_fee) + floatval($this->tuition_fee);

        // Generate QR code payment text
        // Format: BCEL One QR or plain details
        $qrText = "OnePay:A/C={$this->bank_account_number}&Amt=" . intval($total) . "&Desc=" . $invoiceNumber;

        // Generate and save QR Code
        $qrName = 'qr_' . $invoiceNumber . '.png';
        $qrPath = 'qrcodes/' . $qrName;
        
        try {
            Storage::disk('public')->makeDirectory('qrcodes');
            // Generate PNG using GD
            $qrData = QrCode::format('png')->size(250)->margin(1)->generate($qrText);
            Storage::disk('public')->put($qrPath, $qrData);
            $savedQrPath = $qrName;
        } catch (\Exception $e) {
            // Fallback to null or log error
            $savedQrPath = null;
            SystemLog::create([
                'level' => 'error',
                'message' => "ບໍ່ສາມາດສ້າງ QR Code: " . $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
        }

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'student_id' => $this->student_id,
            'date' => date('Y-m-d'),
            'card_fee' => $this->card_fee,
            'photo_fee' => $this->photo_fee,
            'email_fee' => $this->email_fee,
            'tuition_fee' => $this->tuition_fee,
            'total_amount' => $total,
            'bank_account_number' => $this->bank_account_number,
            'student_email' => $student->email,
            'qr_code_path' => $savedQrPath,
            'payment_status' => 'unpaid',
            'notes' => $this->notes,
            'created_by' => Auth::id(),
        ]);

        SystemLog::create([
            'level' => 'info',
            'message' => "ສ້າງໃບບິນໃໝ່: {$invoiceNumber} ໃຫ້ນັກສຶກສາ {$student->full_name} ຍອດລວມ " . number_format($total) . " LAK",
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'create_invoice', 'invoice_id' => $invoice->id, 'invoice_number' => $invoiceNumber]
        ]);

        session()->flash('message', 'ສ້າງໃບບິນຮຽບຮ້ອຍແລ້ວ.');
        $this->showCreateModal = false;
    }

    public function openPaymentModal($id)
    {
        $invoice = Invoice::findOrFail($id);
        $this->selectedInvoiceId = $invoice->id;
        $this->payment_status = $invoice->payment_status ?: 'paid';
        $this->payment_date = $invoice->payment_date ? $invoice->payment_date->format('Y-m-d') : date('Y-m-d');
        $this->showPaymentModal = true;
    }

    public function updatePaymentStatus()
    {
        $this->validate([
            'payment_status' => 'required|in:unpaid,paid,cancelled',
            'payment_date' => 'nullable|date',
        ]);

        $invoice = Invoice::findOrFail($this->selectedInvoiceId);
        $student = $invoice->student;

        $invoice->update([
            'payment_status' => $this->payment_status,
            'payment_date' => $this->payment_status === 'paid' ? ($this->payment_date ?: date('Y-m-d')) : null,
        ]);

        SystemLog::create([
            'level' => 'info',
            'message' => "ອັບເດດສະຖານະໃບບິນ {$invoice->invoice_number} ເປັນ {$this->payment_status}",
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'update_invoice_payment', 'invoice_number' => $invoice->invoice_number, 'status' => $this->payment_status]
        ]);

        session()->flash('message', 'ອັບເດດສະຖານະການຊຳລະເງິນສຳເລັດ.');
        $this->showPaymentModal = false;
    }

    public function deleteInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invNumber = $invoice->invoice_number;

        // Delete QR code file if exists
        if ($invoice->qr_code_path) {
            Storage::disk('public')->delete('qrcodes/' . $invoice->qr_code_path);
        }

        $invoice->delete();

        SystemLog::create([
            'level' => 'warning',
            'message' => "ລຶບໃບບິນ: {$invNumber}",
            'user_id' => Auth::id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'context' => ['action' => 'delete_invoice', 'invoice_number' => $invNumber]
        ]);

        session()->flash('message', 'ລຶບໃບບິນສຳເລັດແລ້ວ.');
    }

    private function formatLaoMonth($date)
    {
        $laoMonths = [
            1 => 'ມັງກອນ', 2 => 'ກຸມພາ', 3 => 'ມີນາ',
            4 => 'ເມສາ', 5 => 'ພຶດສະພາ', 6 => 'ມິຖຸນາ',
            7 => 'ກໍລະກົດ', 8 => 'ສິງຫາ', 9 => 'ກັນຍາ',
            10 => 'ຕຸລາ', 11 => 'ພະຈິກ', 12 => 'ທັນວາ',
        ];
        return $laoMonths[$date->month] . ' ' . $date->format('y');
    }

    public function render()
    {
        // Calculate statistics
        $totalPaid = Invoice::where('payment_status', 'paid')->sum('total_amount');
        $totalUnpaid = Invoice::where('payment_status', 'unpaid')->sum('total_amount');
        $totalCancelled = Invoice::where('payment_status', 'cancelled')->sum('total_amount');

        // Generate 6 months chart data
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $monthStart = $monthDate->copy()->startOfMonth()->toDateString();
            $monthEnd = $monthDate->copy()->endOfMonth()->toDateString();
            $monthLabel = $this->formatLaoMonth($monthDate);

            $total = Invoice::where('payment_status', 'paid')
                ->whereBetween('payment_date', [$monthStart, $monthEnd])
                ->sum('total_amount');

            $monthlyRevenue[] = [
                'month' => $monthLabel,
                'total' => floatval($total),
            ];
        }

        $query = Invoice::query()->with('student');

        if ($this->searchInvoice) {
            $query->where(function ($q) {
                $q->where('invoice_number', 'LIKE', '%' . $this->searchInvoice . '%')
                  ->orWhereHas('student', function ($sq) {
                      $sq->where('first_name', 'LIKE', '%' . $this->searchInvoice . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $this->searchInvoice . '%')
                        ->orWhere('student_id', 'LIKE', '%' . $this->searchInvoice . '%');
                  });
            });
        }

        if ($this->filterStatus) {
            $query->where('payment_status', $this->filterStatus);
        }

        $invoices = $query->orderBy('created_at', 'desc')->paginate(10);
        $students = Student::when($this->studentSearch, function ($q) {
            $q->where(function ($sq) {
                $sq->where('student_id', 'LIKE', '%' . $this->studentSearch . '%')
                   ->orWhere('first_name', 'LIKE', '%' . $this->studentSearch . '%')
                   ->orWhere('last_name', 'LIKE', '%' . $this->studentSearch . '%');
            });
        })->orderBy('first_name', 'asc')->get();

        return view('livewire.invoice-management', [
            'invoices' => $invoices,
            'students' => $students,
            'totalPaid' => $totalPaid,
            'totalUnpaid' => $totalUnpaid,
            'totalCancelled' => $totalCancelled,
            'monthlyRevenue' => $monthlyRevenue,
        ])->layout('layouts.app');
    }
}
