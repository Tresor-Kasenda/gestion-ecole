<!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Rapport de Paiements</title>
                    <style>
                        @page {
                            margin: 1cm;
                        }
                        body {
                            font-family: Arial, sans-serif;
                            font-size: 11px;
                            margin: 0;
                            padding: 0;
                        }
                        .page-break {
                            page-break-after: always;
                        }
                        .header {
                            text-align: center;
                            margin-bottom: 15px;
                            border-bottom: 1px solid #ddd;
                            padding-bottom: 10px;
                        }
                        .header h2 {
                            margin: 0;
                            padding: 0;
                            font-size: 18px;
                        }
                        .header p {
                            margin: 5px 0 0;
                        }
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            margin-top: 10px;
                        }
                        th, td {
                            border: 1px solid #ddd;
                            padding: 5px;
                            text-align: left;
                            font-size: 10px;
                        }
                        th {
                            background-color: #f2f2f2;
                            font-weight: bold;
                        }
                        .footer {
                            position: fixed;
                            bottom: 0;
                            width: 100%;
                            text-align: right;
                            font-style: italic;
                            font-size: 9px;
                            border-top: 1px solid #ddd;
                            padding-top: 5px;
                        }
                        .total {
                            font-weight: bold;
                            background-color: #f9f9f9;
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Rapport de Paiements</h2>
                        <p>Date d'impression: {{ $date }}</p>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Élève</th>
                                <th>Option</th>
                                <th>Classe</th>
                                <th>Mois</th>
                                <th>Type</th>
                                <th>Montant total</th>
                                <th>Montant payé</th>
                                <th>Solde</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                                <td>{{ $payment->student->name }}</td>
                                <td>{{ $payment->student->option->nom }}</td>
                                <td>{{ $payment->student->classe->name }}</td>
                                <td>{{ $payment->month->name }}</td>
                                <td>{{ $payment->type_payment->name }}</td>
                                <td>FC {{ number_format($payment->total_amount, 2) }}</td>
                                <td>FC {{ number_format($payment->amount, 2) }}</td>
                                <td>FC {{ number_format($payment->balance, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="total">
                                <td colspan="5">Total</td>
                                <td>FC {{ number_format($payments->sum('total_amount'), 2) }}</td>
                                <td>FC {{ number_format($payments->sum('amount'), 2) }}</td>
                                <td>FC {{ number_format($payments->sum('balance'), 2) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="footer">
                        <p>Page {PAGE_NUM} sur {PAGE_COUNT} | Rapport généré {{ $hasFilters ? 'avec filtres' : 'sans filtres' }}</p>
                    </div>
                </body>
                </html>
