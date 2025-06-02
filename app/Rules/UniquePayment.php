<?php

namespace App\Rules;

use App\Models\Payment;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniquePayment implements ValidationRule
{
    protected $studentId;
    protected $monthId;
    protected $typePaymentId;
    protected $paymentDate;
    protected $excludeId;

    public function __construct($studentId, $monthId, $typePaymentId, $paymentDate, $excludeId = null)
    {
        $this->studentId = $studentId;
        $this->monthId = $monthId;
        $this->typePaymentId = $typePaymentId;
        $this->paymentDate = $paymentDate;
        $this->excludeId = $excludeId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = Payment::where('student_id', $this->studentId)
            ->where('month_id', $this->monthId)
            ->where('type_payment_id', $this->typePaymentId)
            ->whereDate('payment_date', Carbon::parse($this->paymentDate));

        if ($this->excludeId) {
            $query->where('id', '!=', $this->excludeId);
        }

        if ($query->exists()) {
            $fail("Un paiement existe déjà pour cet élève avec ces mêmes critères (mois, type de paiement et date).");
        }
    }
}
