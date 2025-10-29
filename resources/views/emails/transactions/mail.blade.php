<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->invoice_number }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: 'Segoe UI', Roboto, sans-serif;">
    <div
        style="max-width:600px; margin:40px auto; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background-color:#2563eb; padding:24px; text-align:center;">
            <img src="{{ asset('assets/images/logo mora.png') }}" alt="Logo" style="height:120px; margin-bottom:12px;">
            <h1 style="color:#ffffff; font-size:20px; font-weight:600; margin:0;">Invoice Pembayaran Anda</h1>
        </div>

        <!-- Body -->
        <div style="padding:32px;">
            <p style="color:#374151; font-size:16px; margin-bottom:16px;">
                Halo <strong>{{ $transaction->user->name }}</strong>,
            </p>
            <p style="color:#4b5563; font-size:15px; margin-bottom:24px;">
                Terima kasih telah melakukan pembelian paket <strong>{{ $transaction->plan->name }}</strong>.
            </p>

            <table style="width:100%; border-collapse:collapse; margin-bottom:24px;">
                <tr>
                    <td style="padding:8px 0; color:#6b7280;">Nomor Invoice</td>
                    <td style="padding:8px 0; text-align:right; color:#111827;">{{ $transaction->invoice_number }}</td>
                </tr>
                <tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:8px 0; color:#6b7280;">Total Pembayaran</td>
                    <td style="padding:8px 0; text-align:right; color:#111827;">
                        Rp{{ number_format($transaction->amount, 0, ',', '.') }}
                    </td>
                </tr>
                <tr style="border-top:1px solid #e5e7eb;">
                    <td style="padding:8px 0; color:#6b7280;">Status</td>
                    <td style="padding:8px 0; text-align:right;">
                        @php
                            $status = strtolower($transaction->status);
                            $badgeStyles = match ($status) {
                                'success' => 'background-color:#dcfce7;color:#15803d;',
                                'pending' => 'background-color:#fef9c3;color:#854d0e;',
                                'failed' => 'background-color:#fee2e2;color:#991b1b;',
                                default => 'background-color:#e5e7eb;color:#374151;',
                            };
                            $statusLabel = match ($status) {
                                'success' => 'Berhasil',
                                'pending' => 'Menunggu Pembayaran',
                                'failed' => 'Gagal',
                                default => ucfirst($status),
                            };
                        @endphp
                        <span
                            style="padding:4px 10px; border-radius:9999px; font-weight:600; font-size:13px; {{ $badgeStyles }}">
                            {{ $statusLabel }}
                        </span>
                    </td>
                </tr>
            </table>
            @if ($transaction->status === 'pending')
                <div style="text-align:center; margin-top:20px;">
                    <a href="{{ $transaction->payment_url }}"
                        style="background-color:#2563eb; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; display:inline-block;">
                        Bayar Sekarang
                    </a>
                </div>
            @else
                <div style="text-align:center; margin-top:20px;">
                    <a href="{{ route('billing.index') }}"
                        style="background-color:#2563eb; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; display:inline-block;">
                        Lihat Paket Aktif
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div style="background-color:#f9fafb; text-align:center; padding:20px;">
            <p style="color:#9ca3af; font-size:13px; margin:0;">
                Terima kasih,<br><strong>{{ config('app.name') }}</strong>
            </p>
        </div>
    </div>
</body>

</html>
