<?php

namespace App\Console\Commands;

use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendPaymentReminders extends Command
{
    protected $signature = 'payments:send-reminders';
    protected $description = 'Envoie des rappels pour les paiements en retard';

    public function handle()
    {
        $overduePayments = Payment::overdue()
            ->with(['student', 'type_payment'])
            ->get();

        foreach ($overduePayments as $payment) {
            // Pour l'instant, on log juste les rappels
            // TODO: Implémenter l'envoi d'emails ou de SMS
            Log::info("Rappel de paiement pour {$payment->student->name}", [
                'student_id' => $payment->student_id,
                'payment_id' => $payment->id,
                'amount_due' => $payment->balance,
                'due_date' => $payment->due_date->format('Y-m-d'),
            ]);
        }

        $this->info("{$overduePayments->count()} rappels de paiement ont été traités.");
    }
}
