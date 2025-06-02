<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class PaymentInvoiceController extends Controller
{
    public function __invoke(Payment $payment): Response
    {
        $invoiceDate = $payment->payment_date;
        $month = $payment->month->name;
        $workerName = str($payment->amount)->replace(' ', '')->headline();
        $fileName = "invoice_{$invoiceDate}_{$workerName}.pdf";
        $total = 0;
        $school = Ecole::first();
        $classe = $payment->load('student.classe');

        $pdf = PDF::loadView('print', compact('payment', 'classe', 'fileName', 'total', 'school', 'month'));


        return $pdf->stream($fileName);
    }
}
