<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentReportController extends Controller
{
    public function generateMonthlyReport(Request $request)
    {
        $month = $request->input('month', now()->format('m'));
        $year = $request->input('year', now()->format('Y'));

        $date = Carbon::createFromDate($year, $month, 1);

        $payments = Payment::whereYear('payment_date', $year)
            ->whereMonth('payment_date', $month)
            ->with(['student', 'type_payment'])
            ->get();

        $statistics = [
            'total_amount' => $payments->sum('amount'),
            'completed_payments' => $payments->where('is_completed', true)->count(),
            'partial_payments' => $payments->where('status', 'partial')->count(),
            'overdue_payments' => $payments->where('status', 'overdue')->count(),
        ];

        $pdf = PDF::loadView('reports.monthly', [
            'payments' => $payments,
            'statistics' => $statistics,
            'date' => $date,
        ]);

        return $pdf->download("rapport-paiements-{$year}-{$month}.pdf");
    }

    public function generateStudentReport(Student $student)
    {
        $payments = Payment::where('student_id', $student->id)
            ->with(['type_payment', 'month'])
            ->orderBy('payment_date', 'desc')
            ->get();

        $statistics = [
            'total_paid' => $payments->sum('amount'),
            'total_due' => $payments->sum('balance'),
            'payment_history' => $payments->groupBy(function ($payment) {
                return $payment->payment_date->format('Y-m');
            }),
        ];

        $pdf = PDF::loadView('reports.student', [
            'student' => $student,
            'payments' => $payments,
            'statistics' => $statistics,
        ]);

        return $pdf->download("historique-paiements-{$student->matricule}.pdf");
    }
}
