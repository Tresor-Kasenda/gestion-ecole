<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport mensuel des paiements</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .statistics { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rapport mensuel des paiements</h1>
        <p>{{ $date->format('F Y') }}</p>
    </div>

    <div class="statistics">
        <h3>Statistiques</h3>
        <ul>
            <li>Montant total : {{ number_format($statistics['total_amount'], 2) }} FC</li>
            <li>Paiements complétés : {{ $statistics['completed_payments'] }}</li>
            <li>Paiements partiels : {{ $statistics['partial_payments'] }}</li>
            <li>Paiements en retard : {{ $statistics['overdue_payments'] }}</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Étudiant</th>
                <th>Type</th>
                <th>Montant</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td>{{ $payment->student->name }}</td>
                <td>{{ $payment->type_payment->name }}</td>
                <td>{{ number_format($payment->amount, 2) }} FC</td>
                <td>{{ $payment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
