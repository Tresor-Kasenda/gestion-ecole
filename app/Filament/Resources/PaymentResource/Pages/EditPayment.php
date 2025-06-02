<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $originalAmount = $data['amount'] ?? 0;
        $originalTotalAmount = $data['total_amount'] ?? 0;

        if (! empty($data['outstanding_payment_id']) && ! empty($data['has_outstanding']) && $data['has_outstanding']) {
            $outstandingPayment = Payment::find($data['outstanding_payment_id']);

            if ($outstandingPayment && $outstandingPayment->balance > 0) {
                // Store original outstanding amount for reference
                $originalOutstandingAmount = $outstandingPayment->balance;

                // Determine how much of the current payment to apply to outstanding balance
                $amountForOutstanding = min($originalAmount, $outstandingPayment->balance);

                // Update the outstanding payment
                $outstandingPayment->amount += $amountForOutstanding;
                $outstandingPayment->balance = max(0, $outstandingPayment->balance - $amountForOutstanding);
                $outstandingPayment->is_completed = ($outstandingPayment->balance <= 0);
                $outstandingPayment->save();

                // Adjust the amount for the current payment
                $data['amount'] = max(0, $originalAmount - $amountForOutstanding);

                // Add info about the outstanding payment being handled
                $data['outstanding_paid'] = $amountForOutstanding;
                $data['outstanding_original'] = $originalOutstandingAmount;
            }
        }

        // Ensure total_amount is set
        $data['total_amount'] = $originalTotalAmount;

        // Calculate balance for the current payment
        $data['balance'] = max(0, $data['total_amount'] - $data['amount']);
        $data['is_completed'] = ($data['balance'] <= 0);

        // Update the payment record
        $record->update($data);

        return $record;
    }
}
