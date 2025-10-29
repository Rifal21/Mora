<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Langganan {{ $subscription->plan->name }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f3f4f6; font-family: 'Segoe UI', Roboto, sans-serif;">
    <div
        style="max-width:600px; margin:40px auto; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 10px 25px rgba(0,0,0,0.05);">

        <!-- Header -->
        <div style="background-color:#2563eb; padding:24px; text-align:center;">
            <img src="{{ asset('assets/images/logo mora.png') }}" alt="Logo Mora"
                style="height:100px; margin-bottom:12px;">
            <h1 style="color:#ffffff; font-size:20px; font-weight:600; margin:0;">Pemberitahuan Langganan</h1>
        </div>

        <!-- Content -->
        <div style="padding:32px;">
            <p style="color:#374151; font-size:16px; margin-bottom:16px;">
                Halo <strong>{{ $subscription->user->name }}</strong> 👋
            </p>

            <p style="color:#4b5563; font-size:15px; margin-bottom:24px;">
                Kami ingin mengingatkan bahwa langganan Anda untuk paket
                <strong>{{ $subscription->plan->name }}</strong>
                akan berakhir pada
                <span style="color:#dc2626; font-weight:600;">
                    {{ \Carbon\Carbon::parse($subscription->end_date)->translatedFormat('d F Y H:i') }}
                </span>.
            </p>

            @php
                use Carbon\Carbon;

                $duration = $subscription->plan->duration_days;
                $now = Carbon::now();
                $end = Carbon::parse($subscription->end_date);

                $diff = $now->diff($end);
                $daysLeft = $diff->d;
                $hoursLeft = $diff->h;
                $minutesLeft = $diff->i;

                if ($duration <= 1) {
                    $type = 'daily';
                } elseif ($duration <= 31) {
                    $type = 'monthly';
                } else {
                    $type = 'yearly';
                }
            @endphp

            <div style="text-align:center; margin-bottom:20px;">
                @if ($type === 'daily')
                    <span
                        style="padding:6px 12px; background-color:#fef9c3; color:#854d0e; border-radius:9999px; font-weight:600; font-size:14px; display:inline-block;">
                        @if ($hoursLeft < 0 && $minutesLeft < 0)
                            ⏰ Paket Habis
                        @else
                            ⏰ Tersisa
                            {{ $hoursLeft > 0 ? $hoursLeft . ' jam ' : '' }}
                            {{ $minutesLeft > 0 ? $minutesLeft . ' menit' : '' }}
                            lagi
                        @endif
                    </span>
                @elseif ($type === 'monthly')
                    <span
                        style="padding:6px 12px; background-color:#ffedd5; color:#9a3412; border-radius:9999px; font-weight:600; font-size:14px; display:inline-block;">
                        @if ($daysLeft < 0 && $hoursLeft < 0 && $minutesLeft < 0)
                            ⏰ Paket Habis
                        @else
                            📅 Tersisa
                            {{ $daysLeft > 0 ? $daysLeft . ' hari ' : '' }}
                            {{ $hoursLeft > 0 ? $hoursLeft . ' jam ' : '' }}
                            {{ $minutesLeft > 0 ? $minutesLeft . ' menit' : '' }}
                            lagi
                        @endif
                    </span>
                @elseif ($type === 'yearly')
                    <span
                        style="padding:6px 12px; background-color:#dbeafe; color:#1e40af; border-radius:9999px; font-weight:600; font-size:14px; display:inline-block;">
                        @if ($daysLeft < 0 && $hoursLeft < 0 && $minutesLeft < 0)
                            ⏰ Paket Habis
                        @else
                            📅 Tersisa
                            {{ $daysLeft > 0 ? $daysLeft . ' hari ' : '' }}
                            {{ $hoursLeft > 0 ? $hoursLeft . ' jam ' : '' }}
                            {{ $minutesLeft > 0 ? $minutesLeft . ' menit' : '' }}
                            lagi
                        @endif
                    </span>
                @endif
            </div>


            <p style="color:#4b5563; font-size:15px; margin-bottom:32px; text-align:center;">
                Jangan lupa untuk memperpanjang agar tetap menikmati fitur <strong>PRO</strong> tanpa gangguan.
            </p>

            <div style="text-align:center;">
                <a href="{{ url('/plans') }}"
                    style="background-color:#2563eb; color:white; padding:12px 24px; border-radius:8px; text-decoration:none; font-weight:600; display:inline-block;">
                    🔄 Perpanjang Sekarang
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color:#f9fafb; text-align:center; padding:20px;">
            <p style="color:#9ca3af; font-size:13px; margin:0;">
                Terima kasih telah menggunakan layanan <strong>Mora</strong>.
            </p>
        </div>
    </div>
</body>

</html>
