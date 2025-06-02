<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $fileName ?? "" }}</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }

        p {
            margin-bottom: -13px
        }

        span {
            font-weight: bold;
        }
    </style>
    <style>
        .demo {
            border: 1px solid #050505;
            border-collapse: collapse;
            padding: 5px;
            width: 100%;
        }

        .demo th {
            border: 1px solid #050505;
            padding: 5px;
            background: #A39F9F;
        }

        .demo td {
            border: 1px solid #050505;
            text-align: center;
            padding: 5px;
        }
    </style>

</head>

<body class="antialiased">
    <h2 style="margin: -2px;">{{ $school->nom  ?? "" }}</h2>
    <hr style="border: 1px solid">
    <table width="100%" border="0" style="margin:20px 0; justify-content: space-between; justify-items: center; padding: 1rem; background: aqua">
        <tr style="vertical-align: top;">
            <td width="70">Addresse</td>
            <td width="1">:</td>
            <td>{{ $school->adresse }} <br> {{ $payment->telephone }}</td>
        </tr>
        <tr>
            <td>Date de paiement</td>
            <td>:</td>
            <td>{{ $payment->payment_date }}</td>
        </tr>
        <tr>
            <td>Motif de paiement</td>
            <td>:</td>
            <td>{{ $month ?? "" }}</td>
        </tr>
        <tr>
            <td>Type de paiement</td>
            <td>:</td>
            <td>{{ $payment->type_payment->name ?? "" }}</td>
        </tr>
        <tr>
            <td>Classe : </td>
            <td>:</td>
            <td>{{ $payment->student->classe->name ?? "" }}</td>
        </tr>
    </table>

    <table class="demo">
        <thead>
            <tr>
                <th>Numero</th>
                <th>Nom, Post-Nom et Prenom</th>
                <th>Option</th>
                <th>Date payement</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody >
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->student->name . "-". $payment->student->lastname. "-". $payment->student->firstname }}</td>
                <td>{{ $payment->student->option->nom ?? " " }}</td>
                <td>{{ $payment->payment_date }}</td>
                <td>FC {{ number_format($payment->amount, 2, ". ") }}</td>
            </tr>

        </tbody>
    </table>
</body>

</html>
