<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 5px 0; }
        .content { line-height: 1.6; }
        .signature { margin-top: 50px; }
        .signature table { width: 100%; }
        .print-hide { margin-top: 20px; text-align: center; }
        @media print {
            .print-hide { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>KEMENTERIAN PERTAHANAN REPUBLIK INDONESIA</h2>
        <h3>SURAT IZIN CUTI</h3>
        <hr>
    </div>

    <div class="content">
        <table style="width: 100%; margin-bottom: 20px;">
            <tr>
                <td width="150">Nama</td>
                <td>: <?= htmlspecialchars($cuti['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td>NIP/NRP</td>
                <td>: <?= htmlspecialchars($cuti['nip']) ?></td>
            </tr>
            <tr>
                <td>Pangkat/Golongan</td>
                <td>: <?= htmlspecialchars($cuti['pangkat']) ?></td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>: <?= htmlspecialchars($cuti['jabatan']) ?></td>
            </tr>
            <tr>
                <td>Jenis Cuti</td>
                <td>: <?= htmlspecialchars($cuti['nama_cuti']) ?></td>
            </tr>
            <tr>
                <td>Periode Cuti</td>
                <td>: <?= date('d F Y', strtotime($cuti['mulai'])) ?> s/d <?= date('d F Y', strtotime($cuti['berakhir'])) ?></td>
            </tr>
            <tr>
                <td>Tujuan</td>
                <td>: <?= htmlspecialchars($cuti['tujuan']) ?></td>
            </tr>
        </table>

        <p>Berdasarkan permohonan yang bersangkutan, maka diberikan izin cuti sesuai dengan ketentuan yang berlaku.</p>
        
        <div class="signature">
            <table>
                <tr>
                    <td width="50%"></td>
                    <td style="text-align: center;">
                        Jakarta, <?= date('d F Y') ?><br><br>
                        <strong>KEPALA BAGIAN KEPEGAWAIAN</strong><br><br><br><br>
                        <u>_______________________</u><br>
                        NIP. ___________________
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="print-hide">
        <button onclick="window.print()" class="btn btn-primary">Cetak Surat</button>
        <button onclick="window.close()" class="btn btn-secondary">Tutup</button>
    </div>

    <script>
        // Auto print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
