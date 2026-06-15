<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'invoice_number',
        'student_id',
        'date',
        'card_fee',
        'photo_fee',
        'email_fee',
        'tuition_fee',
        'total_amount',
        'bank_account_number',
        'student_email',
        'parent_email',
        'qr_code_path',
        'payment_status',
        'payment_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'payment_date' => 'date',
        'card_fee' => 'float',
        'photo_fee' => 'float',
        'email_fee' => 'float',
        'tuition_fee' => 'float',
        'total_amount' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
