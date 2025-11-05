<!DOCTYPE html>
<html lang="en">

<head>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <meta charset="utf-8">
    <title>Laporan Sisa Cuti Pegawai</title>
    <style>
        @media print {
            body {
                font-family: 'Arial', sans-serif;
                font-size: 12px;
                color: #000;
                margin: 0;
                padding: 15px;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none !important;
            }

            .header-kop {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
            }

            .logo {
                width: 70px;
                height: 70px;
                margin-right: 10px;
                object-fit: contain;
            }

            .kop-text {
                flex: 1;
                text-align: center;
            }

            .kop-text h2 {
                margin: 0;
                padding: 0;
                font-size: 20px;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.2;
            }

            .judul-laporan {
                text-align: center;
                margin: 15px 0;
            }

            .judul-laporan h3 {
                margin: 0;
                padding: 0;
                font-size: 13px;
                text-transform: uppercase;
                text-decoration: underline;
            }

            .judul-laporan p {
                margin: 3px 0;
                font-size: 11px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2 !important;
                font-weight: bold;
                text-align: center;
            }

            .no {
                width: 5%;
                text-align: center;
            }

            .nama {
                width: 45%;
            }

            .nip {
                width: 30%;
                text-align: center;
            }

            .sisa {
                width: 20%;
                text-align: center;
            }
        }

        @media screen {
            body {
                font-family: 'Arial', sans-serif;
                font-size: 12px;
                color: #333;
                margin: 20px;
                padding: 15px;
                background: #f5f5f5;
            }

            .print-container {
                background: white;
                padding: 20px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                max-width: 1000px;
                margin: 0 auto;
            }

            .header-kop {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
            }

            .logo {
                width: 70px;
                height: 70px;
                margin-right: 10px;
                object-fit: contain;
            }

            .kop-text {
                flex: 1;
                text-align: center;
            }

            .kop-text h2 {
                margin: 0;
                padding: 0;
                font-size: 14px;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.2;
            }

            .judul-laporan {
                text-align: center;
                margin: 15px 0;
            }

            .judul-laporan h3 {
                margin: 0;
                padding: 0;
                font-size: 13px;
                text-transform: uppercase;
                text-decoration: underline;
            }

            .judul-laporan p {
                margin: 3px 0;
                font-size: 11px;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
            }

            th,
            td {
                border: 1px solid #333;
                padding: 6px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
                font-weight: bold;
                text-align: center;
            }

            .no {
                width: 5%;
                text-align: center;
            }

            .nama {
                width: 45%;
            }

            .nip {
                width: 30%;
                text-align: center;
            }

            .sisa {
                width: 20%;
                text-align: center;
            }

            .action-buttons {
                text-align: center;
                margin: 20px 0;
                padding: 10px;
            }

            .btn-print {
                background: #007bff;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                cursor: pointer;
                font-size: 14px;
            }

            .btn-print:hover {
                background: #0056b3;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Action Buttons (Hanya tampil di screen) -->
        <div class="text-center my-4 d-print-none">
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i> Cetak Laporan
    </button>
    <button type="button" class="btn btn-secondary ms-2" onclick="window.close()">
        <i class="fa-solid fa-circle-xmark me-2"></i> Tutup
    </button>
</div>

        <!-- Kop Surat dengan LOGO -->
        <div class="header-kop">
            <img src="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" class="logo" alt="Logo Kemhan">
            <div class="kop-text">
                <h2>kementerian pertahanan <br> republik indonesia</h2>
            </div>
        </div>

        <!-- Judul Laporan -->
        <div class="judul-laporan">
            <h3>laporan sisa cuti pegawai</h3>
            <p>Sistem Informasi Cuti Pegawai Kementerian Pertahanan</p>
            <?php
            setlocale(LC_TIME, 'id_ID.UTF-8', 'id_ID', 'Indonesian_indonesia');
            $tanggal = strftime('%d %B %Y');
            ?>
            <p>Per Tanggal: <?= ucfirst($tanggal) ?></p>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th class="nama">Nama Lengkap</th>
                    <th class="nip">NIP/NRP</th>
                    <th class="sisa">Sisa Cuti Tahunan</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                foreach ($pegawai as $p):
                    ?>
                    <tr>
                        <td class="no"><?= $no++; ?></td>
                        <td class="nama"><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                        <td class="nip"><?= htmlspecialchars($p['nip']); ?></td>
                        <td class="sisa"><?= htmlspecialchars($p['sisa_cuti']); ?> Hari</td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($pegawai)): ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">Tidak ada data pegawai</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer Info -->
        <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?> oleh Sistem Informasi Cuti Pegawai</p>
        </div>
    </div>

    <script>
        // Auto print jika diinginkan
        window.onload = function() {
            window.print();
        // };

        // After print event
        window.onafterprint = function () {
            // Optional: close window after print
            // window.close();
        };
    </script>
</body>

</html>