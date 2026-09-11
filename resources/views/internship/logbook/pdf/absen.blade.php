<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 24px; border-bottom: 2px solid #502D55; padding-bottom: 16px; }
        .header h1 { font-size: 16px; margin: 0 0 6px; color: #502D55; }
        .header p { margin: 3px 0; font-size: 12px; color: #555; }
        .info { margin-bottom: 20px; }
        .info p { margin: 4px 0; font-size: 12px; }
        .info strong { color: #502D55; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px 10px; text-align: center; font-size: 11px; }
        th { background: #502D55; color: #fff; font-weight: 600; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) { background: #f9f5f8; }
        .status-hadir { color: #2E7D53; font-weight: 600; }
        .status-lembur { color: #A66A1E; font-weight: 600; }
        .status-aktif { color: #935073; font-weight: 600; }
        .kosong { text-align: center; color: #999; padding: 20px; font-style: italic; }
        .footer { margin-top: 24px; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RECAP ABSENSI</h1>
        <p>Periode: {{ $periodText }}</p>
    </div>

    <div class="info">
        <p><strong>Nama Peserta:</strong> {{ $user->name ?? '-' }}</p>
        <p><strong>Asal Sekolah:</strong> {{ $user->schools ?? '-' }}</p>
        <p><strong>Periode Laporan:</strong> {{ $periodText }}</p>
    </div>

    @if($logbooks->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="30">No</th>
                    <th>Tanggal</th>
                    <th>Jam Masuk</th>
                    <th>Jam Keluar</th>
                    <th>Mulai Lembur</th>
                    <th>Selesai Lembur</th>
                    <th>Durasi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logbooks as $i => $log)
                    @php
                        $clockIn = $log->clock_in;
                        $clockOut = $log->clock_out;
                        $overtimeStart = $log->overtime_started_at;
                        $overtimeEnd = $log->overtime_ended_at;

                        // Hitung durasi
                        if ($clockOut) {
                            $diff = $clockIn->diff($clockOut);
                        } else {
                            $diff = $clockIn->diff(now());
                        }
                        $hours = floor($diff->h + ($diff->i / 60) + ($diff->d * 24));
                        $minutes = $diff->i;
                        $durasi = $hours . 'j ' . $minutes . 'm';

                        // Status
                        if ($overtimeStart && $overtimeEnd) {
                            $status = 'Lembur';
                            $statusClass = 'status-lembur';
                        } elseif ($overtimeStart && !$overtimeEnd) {
                            $status = 'Sedang Lembur';
                            $statusClass = 'status-aktif';
                        } else {
                            $status = 'Hadir';
                            $statusClass = 'status-hadir';
                        }
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $clockIn->format('d/m/Y') }}</td>
                        <td>{{ $clockIn->format('H:i') }}</td>
                        <td>{{ $clockOut ? $clockOut->format('H:i') : '-' }}</td>
                        <td>{{ $overtimeStart ? $overtimeStart->format('H:i') : '-' }}</td>
                        <td>{{ $overtimeEnd ? $overtimeEnd->format('H:i') : '-' }}</td>
                        <td>{{ $durasi }}</td>
                        <td class="{{ $statusClass }}">{{ $status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
    @else
        <div class="kosong">
            Tidak ada data absensi untuk periode {{ $periodText }}.
        </div>
    @endif
</body>
</html>
