<?php

use App\Http\Controllers\PaymentInvoiceController;
use Illuminate\Support\Facades\Route;


Route::redirect('/', '/admin/login');

Route::get('payment/{payment}', PaymentInvoiceController::class)->name('payment');

Route::get('reports/pdf', [App\Http\Controllers\ReportPdfController::class, 'generatePdf'])
    ->name('report.generate-pdf')
    ->middleware(['auth']);
