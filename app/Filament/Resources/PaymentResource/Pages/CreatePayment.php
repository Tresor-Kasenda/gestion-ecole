<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePayment extends CreateRecord
{
    protected static string $resource = PaymentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $originalAmount = $data['amount'] ?? 0;
        $originalTotalAmount = $data['total_amount'] ?? 0;

        if (! empty($data['outstanding_payment_id']) && ! empty($data['has_outstanding']) && $data['has_outstanding']) {
            $outstandingPayment = Payment::find($data['outstanding_payment_id']);

            if ($outstandingPayment && $outstandingPayment->balance > 0) {
                // Store original outstanding amount for reference
                $originalOutstandingAmount = $outstandingPayment->balance;

                // Determine how much to apply to outstanding balance
                $amountForOutstanding = min($originalAmount, $outstandingPayment->balance);

                // Update the outstanding payment
                $outstandingPayment->amount += $amountForOutstanding;
                $outstandingPayment->balance = max(0, $outstandingPayment->balance - $amountForOutstanding);
                $outstandingPayment->is_completed = ($outstandingPayment->balance <= 0);
                $outstandingPayment->save();

                // Adjust the amount for the new payment
                $data['amount'] = max(0, $originalAmount - $amountForOutstanding);

                // Add info about the outstanding payment being handled
                $data['outstanding_paid'] = $amountForOutstanding;
                $data['outstanding_original'] = $originalOutstandingAmount;
            }
        }

        // Set the total amount
        $data['total_amount'] = $originalTotalAmount;

        // Calculate balance for the new payment
        $data['balance'] = max(0, $data['total_amount'] - $data['amount']);
        $data['is_completed'] = ($data['balance'] <= 0);

        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $originalAmount = $data['amount'] ?? 0;
        $originalTotalAmount = $data['total_amount'];

        if (! empty($data['outstanding_payment_id']) && ! empty($data['has_outstanding']) && $data['has_outstanding']) {
            $outstandingPayment = Payment::find($data['outstanding_payment_id']);

            if ($outstandingPayment && $outstandingPayment->balance > 0) {
                $originalOutstandingAmount = $outstandingPayment->balance;

                $amountForOutstanding = min($originalAmount, $outstandingPayment->balance);

                // Update the outstanding payment
                $outstandingPayment->amount += $amountForOutstanding;
                $outstandingPayment->balance = max(0, $outstandingPayment->balance - $amountForOutstanding);
                $outstandingPayment->is_completed = ($outstandingPayment->balance <= 0);
                $outstandingPayment->save();

                // Adjust the amount for the new payment
                $data['amount'] = max(0, $originalAmount - $amountForOutstanding);

                // Add info about the outstanding payment being handled
                $data['outstanding_paid'] = $amountForOutstanding;
                $data['outstanding_original'] = $originalOutstandingAmount;
            }
        }

        // Set the total amount
        $data['total_amount'] = $originalTotalAmount;

        // Calculate balance for the new payment
        $data['balance'] = max(0, $data['total_amount'] - $data['amount']);
        $data['is_completed'] = ($data['balance'] <= 0);

        return static::getModel()::create($data);
    }
}
