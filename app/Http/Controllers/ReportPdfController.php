<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportPdfController extends Controller
{
    public function generatePdf(Request $request, $data = null)
    {
        $query = Payment::query()
            ->with(['student.option', 'student.classe', 'month', 'type_payment']);

        // Apply filters if present
        if ($request->has('filters')) {
            $filters = json_decode(base64_decode($request->filters), true);

            // Apply month filter
            if (! empty($filters['month_id'])) {
                $query->whereIn('month_id', $filters['month_id']);
            }

            // Apply option filter
            if (! empty($filters['option_id'])) {
                $query->whereHas('student', function ($q) use ($filters) {
                    $q->whereIn('option_id', $filters['option_id']);
                });
            }

            // Apply classe filter
            if (! empty($filters['student.classe_id'])) {
                $query->whereHas('student', function ($q) use ($filters) {
                    $q->whereIn('classe_id', $filters['student.classe_id']);
                });
            }

            // Apply date range filter
            if (! empty($filters['payment_date']['date_from'])) {
                $query->whereDate('payment_date', '>=', $filters['payment_date']['date_from']);
            }

            if (! empty($filters['payment_date']['date_to'])) {
                $query->whereDate('payment_date', '<=', $filters['payment_date']['date_to']);
            }
        }

        $payments = $query->get();

        $pdf = PDF::loadView('pdf.payment-report', [
            'payments' => $payments,
            'date' => now()->format('d/m/Y'),
            'hasFilters' => $request->has('filters'),
        ])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'dpi' => 150,
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

        if ($request->has('view') && $request->view) {
            return $pdf->stream('rapport-paiements-'.now()->format('Y-m-d').'.pdf');
        } else {
            return $pdf->download('rapport-paiements-'.now()->format('Y-m-d').'.pdf');
        }
    }
}
