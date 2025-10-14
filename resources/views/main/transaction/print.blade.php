<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $transaction->invoice_number }}</title>
    <style>
        @media print {
            @page {
                margin: 0;
            }

            body {
                width: 80mm;
                /* Sesuaikan 80mm atau 58mm */
                font-family: monospace;
                font-size: 12px;
                margin: 0;
                padding: 5px;
            }
        }

        body {
            width: 80mm;
            font-family: monospace;
            font-size: 12px;
            padding: 5px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        .right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="center">
        <strong>{{ session('bisnis_name') ?? 'My Store' }}</strong><br>
        {{ session('bisnis_alamat') ?? '-' }}<br>
        Telp: {{ session('bisnis_telepon') ?? '-' }}<br>
        -----------------------------
    </div>

    <p>Invoice: {{ $transaction->invoice_number }}</p>
    <p>Customer: {{ $transaction->customer_name ?? '-' }}</p>
    <p>Date: {{ $transaction->created_at->format('d/m/Y H:i') }}</p>
    -----------------------------
    <table width="100%">
        @foreach ($transaction->transactionItems as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="right">x{{ $item->quantity }}</td>
            </tr>
            <tr>
                <td colspan="2" class="right">Rp {{ number_format($item->total) }}</td>
            </tr>
        @endforeach
    </table>
    -----------------------------
    <p class="right">TOTAL: Rp {{ number_format($transaction->total_amount) }}</p>
    <p class="center">Payment: {{ ucfirst($transaction->payment_method) }}</p>
    <p class="center">Thank you!</p>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
