<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Historique des paiements - {{ $student->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .student-info { margin: 20px 0; }
        .statistics { margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f5f5f5; }
        .total { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Historique des paiements</h1>
    </div>

    <div class="student-info">
        <h3>Information de l'étudiant</h3>
        <p>Nom: {{ $student->name }} {{ $student->lastname }} {{ $student->firstname }}</p>
        <p>Matricule: {{ $student->matricule }}</p>
        <p>Classe: {{ $student->classe->name }}</p>
    </div>

    <div class="statistics">
        <h3>Résumé</h3>
        <ul>
            <li>Total payé : {{ number_format($statistics['total_paid'], 2) }} FC</li>
            <li>Solde dû : {{ number_format($statistics['total_due'], 2) }} FC</li>
        </ul>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Mois</th>
                <th>Montant payé</th>
                <th>Montant total</th>
                <th>Solde</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $payment)
            <tr>
                <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                <td>{{ $payment->type_payment->name }}</td>
                <td>{{ $payment->month->name }}</td>
                <td>{{ number_format($payment->amount, 2) }} FC</td>
                <td>{{ number_format($payment->total_amount, 2) }} FC</td>
                <td>{{ number_format($payment->balance, 2) }} FC</td>
                <td>{{ $payment->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
