<?php

use App\Http\Controllers\PaymentInvoiceController;
use App\Http\Controllers\PaymentReportController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/admin/login');

Route::get('payment/{payment}', PaymentInvoiceController::class)->name('payment');

Route::get('reports/pdf', [App\Http\Controllers\ReportPdfController::class, 'generatePdf'])
    ->name('report.generate-pdf')
    ->middleware(['auth']);

Route::get('/reports/monthly', [PaymentReportController::class, 'generateMonthlyReport'])
    ->name('reports.monthly');
Route::get('/reports/{student}/student', [PaymentReportController::class, 'generateStudentReport'])
    ->name('payment.invoice');
