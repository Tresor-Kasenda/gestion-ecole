<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $guarded = [];

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
