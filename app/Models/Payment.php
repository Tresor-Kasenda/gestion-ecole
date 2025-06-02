<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $existingPayment = static::where('student_id', $payment->student_id)
                ->where('month_id', $payment->month_id)
                ->where('type_payment_id', $payment->type_payment_id)
                ->whereDate('payment_date', $payment->payment_date)
                ->exists();

            if ($existingPayment) {
                throw new \Exception('Un paiement existe déjà pour cet élève avec ces mêmes critères.');
            }
        });
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function type_payment(): BelongsTo
    {
        return $this->belongsTo(TypePayment::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class, 'option_id');
    }

    public function month(): BelongsTo
    {
        return $this->belongsTo(Month::class);
    }

    public function calculateBalance()
    {
        return $this->total_amount - $this->amount;
    }
}
