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
        td.text-left { text-align: left; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 10px; font-weight: 600; }
        .badge-pending { background: #eee; color: #777; }
        .badge-diajukan { background: #e8e0f0; color: #935073; }
        .badge-disetujui { background: #d5f0e0; color: #2E7D53; }
        .badge-ditolak { background: #f5d5d5; color: #B04040; }
        .kosong { text-align: center; color: #999; padding: 20px; font-style: italic; }
        .footer { margin-top: 24px; text-align: right; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN RECAP LOGBOOK</h1>
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
                    <th>Kegiatan</th>
                    <th>Hasil</th>
                    <th width="80">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logbooks as $i => $log)
                    @php
                        $statusLabel = match($log->approval_status) {
                            'Pending' => '<span class="badge badge-pending">Pending</span>',
                            'Diajukan' => '<span class="badge badge-diajukan">Diajukan</span>',
                            'Disetujui' => '<span class="badge badge-disetujui">Disetujui</span>',
                            'Ditolak' => '<span class="badge badge-ditolak">Ditolak</span>',
                            default => '<span class="badge badge-pending">' . $log->approval_status . '</span>',
                        };
                    @endphp
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $log->clock_in->format('d/m/Y') }}</td>
                        <td>{{ $log->clock_in->format('H:i') }}</td>
                        <td>{{ $log->clock_out ? $log->clock_out->format('H:i') : '-' }}</td>
                        <td class="text-left">{{ $log->activities ?? '-' }}</td>
                        <td class="text-left">{{ $log->result ?? '-' }}</td>
                        <td>{!! $statusLabel !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
    @else
        <div class="kosong">
            Tidak ada data logbook untuk periode {{ $periodText }}.
        </div>
    @endif
</body>
</html>
