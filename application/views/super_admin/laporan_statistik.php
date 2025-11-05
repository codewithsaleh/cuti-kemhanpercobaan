<!DOCTYPE html>
<html lang="id">

<head>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Laporan Statistik Cuti Tim</title>
    <style>
        /* Reset dan Base Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 15mm;
        }

        /* Ukuran A4 */
        @page {
            size: A4;
            margin: 15mm;
        }

        /* Kop Surat */
        .header-kop {
            display: flex;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
            text-align: center;
        }

        .logo {
            width: 70px;
            height: 70px;
            margin-right: 20px;
        }

        .kop-text {
            flex: 1;
            text-align: center;
        }

        .kop-text h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .kop-text h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .kop-text p {
            font-size: 11px;
            margin: 0;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin: 20px 0;
            padding: 10px;
            border: 1px solid #333;
            background: #f8f9fa;
        }

        .judul-laporan h1 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Statistik Box */
        .stats-container {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 20px 0;
        }

        .stats-box {
            width: 23%;
            padding: 12px;
            border: 1px solid #333;
            text-align: center;
            margin-bottom: 10px;
            background: #f8f9fa;
        }

        .stats-number {
            font-size: 20px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .stats-label {
            font-size: 10px;
            color: #666;
            font-weight: bold;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
            font-size: 10px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 4px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        /* Section Headers */
        .section-header {
            background: #e9ecef;
            padding: 8px 12px;
            margin: 20px 0 10px 0;
            border-left: 4px solid #007bff;
            font-weight: bold;
            font-size: 12px;
        }

        /* Footer dan Tanda Tangan */
        .footer {
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .ttd-section {
            float: right;
            text-align: center;
            width: 200px;
        }

        .ttd-space {
            height: 60px;
            margin-bottom: 5px;
        }

        .ttd-line {
            border-top: 1px solid #000;
            margin: 0 auto;
            width: 150px;
        }

        .ttd-info {
            margin-top: 5px;
            font-size: 10px;
        }

        /* Print Styles */
        @media print {
            body {
                margin: 0;
                padding: 15mm;
            }

            .no-print {
                display: none !important;
            }

            .stats-box {
                page-break-inside: avoid;
            }

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Info Box */
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
            font-size: 10px;
        }

        .info-box strong {
            color: #495057;
        }
    </style>
</head>

<body>

    <div class="text-center my-4 d-print-none">
        <button type="button" class="btn btn-primary" onclick="window.print()">
            <i class="fa-solid fa-print me-2"></i> Cetak Laporan
        </button>
        <button type="button" class="btn btn-secondary ms-2" onclick="window.close()">
            <i class="fa-solid fa-circle-xmark me-2"></i> Tutup
        </button>
    </div>
    
    <!-- Kop Surat -->
    <div class="header-kop">
        <img src="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" class="logo" alt="Logo Kemhan">
        <div class="kop-text">
            <h2>kementerian pertahanan republik indonesia</h2>
            <h3>LAPORAN STATISTIK CUTI TIM</h3>
        </div>
    </div>

    <!-- Info Atasan dan Periode -->
    <div class="info-box">
        <strong>Atasan:</strong> <?= htmlspecialchars($atasan['nama_lengkap'] ?? 'N/A') ?> |
        <strong>NIP:</strong> <?= htmlspecialchars($atasan['nip'] ?? 'N/A') ?> |
        <strong>Periode:</strong> Tahun <?= date('Y') ?>
    </div>

    <!-- Statistik Umum -->
    <div class="section-header">STATISTIK UMUM CUTI TIM</div>

    <div class="stats-container">
        <div class="stats-box">
            <div class="stats-number"><?= $total_anggota ?? 0 ?></div>
            <div class="stats-label">TOTAL ANGGOTA TIM</div>
        </div>
        <div class="stats-box">
            <div class="stats-number"><?= $statistik['total_pengajuan'] ?? 0 ?></div>
            <div class="stats-label">TOTAL PENGAJUAN</div>
        </div>
        <div class="stats-box">
            <div class="stats-number"><?= $statistik['total_disetujui'] ?? 0 ?></div>
            <div class="stats-label">DISETUJUI</div>
        </div>
        <div class="stats-box">
            <div class="stats-number"><?= $statistik['total_ditolak'] ?? 0 ?></div>
            <div class="stats-label">DITOLAK</div>
        </div>
    </div>

    <!-- Detail Statistik -->
    <div class="stats-container">
        <div class="stats-box">
            <div class="stats-number"><?= $statistik['total_pending'] ?? 0 ?></div>
            <div class="stats-label">MENUNGGU</div>
        </div>
        <div class="stats-box">
            <div class="stats-number"><?= $statistik['total_hari_cuti'] ?? 0 ?></div>
            <div class="stats-label">TOTAL HARI CUTI</div>
        </div>
        <div class="stats-box">
            <div class="stats-number">
                <?= $statistik['total_pengajuan'] > 0 ?
                    round((($statistik['total_disetujui'] ?? 0) / $statistik['total_pengajuan']) * 100, 1) : 0 ?>%
            </div>
            <div class="stats-label">RASIO DISETUJUI</div>
        </div>
        <div class="stats-box">
            <div class="stats-number">
                <?= $total_anggota > 0 ?
                    round((($statistik['total_pengajuan'] ?? 0) / $total_anggota), 1) : 0 ?>
            </div>
            <div class="stats-label">RATA-RATA CUTI/ANGGOTA</div>
        </div>
    </div>

    <!-- Rekap per Bulan -->
    <div class="section-header">REKAP CUTI PER BULAN (YANG DISETUJUI)</div>

    <table>
        <thead>
            <tr>
                <th width="5%">NO</th>
                <th width="25%">BULAN</th>
                <th width="15%">JUMLAH CUTI</th>
                <th width="15%">TOTAL HARI</th>
                <th width="15%">RATA-RATA HARI</th>
                <th width="25%">PERSENTASE</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total_hari_setahun = array_sum(array_column($monthly_data, 'total_days'));
            $total_cuti_setahun = array_sum(array_column($monthly_data, 'total_cuti'));
            $no = 1;

            foreach ($monthly_data as $data):
                $persentase = $total_hari_setahun > 0 ? round(($data['total_days'] / $total_hari_setahun) * 100, 1) : 0;
                $rata_rata = $data['total_cuti'] > 0 ? round($data['total_days'] / $data['total_cuti'], 1) : 0;
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td class="text-left"><?= htmlspecialchars($data['month_name']) ?></td>
                    <td><?= $data['total_cuti'] ?></td>
                    <td><?= $data['total_days'] ?></td>
                    <td><?= $rata_rata ?></td>
                    <td><?= $persentase ?>%</td>
                </tr>
            <?php endforeach; ?>

            <?php if (!empty($monthly_data)): ?>
                <tr style="font-weight: bold; background-color: #f0f0f0;">
                    <td colspan="2">TOTAL</td>
                    <td><?= $total_cuti_setahun ?></td>
                    <td><?= $total_hari_setahun ?></td>
                    <td><?= $total_cuti_setahun > 0 ? round($total_hari_setahun / $total_cuti_setahun, 1) : 0 ?></td>
                    <td>100%</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data cuti yang disetujui untuk tahun ini</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Statistik per Jenis Cuti -->
    <?php if (!empty($jenis_cuti_stats)): ?>
        <div class="section-header">STATISTIK PER JENIS CUTI</div>

        <table>
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th width="35%">JENIS CUTI</th>
                    <th width="15%">JUMLAH PENGAJUAN</th>
                    <th width="15%">TOTAL HARI</th>
                    <th width="15%">RATA-RATA HARI</th>
                    <th width="15%">PERSENTASE</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_pengajuan_jenis = array_sum(array_column($jenis_cuti_stats, 'total_pengajuan'));
                $no_jenis = 1;

                foreach ($jenis_cuti_stats as $jenis):
                    $persentase_jenis = $total_pengajuan_jenis > 0 ? round(($jenis['total_pengajuan'] / $total_pengajuan_jenis) * 100, 1) : 0;
                    $rata_rata_jenis = $jenis['total_pengajuan'] > 0 ? round($jenis['total_hari'] / $jenis['total_pengajuan'], 1) : 0;
                    ?>
                    <tr>
                        <td><?= $no_jenis++ ?></td>
                        <td class="text-left"><?= htmlspecialchars($jenis['nama_cuti']) ?></td>
                        <td><?= $jenis['total_pengajuan'] ?></td>
                        <td><?= $jenis['total_hari'] ?></td>
                        <td><?= $rata_rata_jenis ?></td>
                        <td><?= $persentase_jenis ?>%</td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Footer dan Tanda Tangan -->
    <div class="footer">
        <div class="ttd-section">
            <div class="ttd-info">Jakarta, <?= date('d F Y') ?></div>
            <div class="ttd-space"></div>
            <div class="ttd-line"></div>
            <div class="ttd-info">
                <strong><?= htmlspecialchars($atasan['nama_lengkap'] ?? 'Atasan') ?></strong><br>
                NIP. <?= htmlspecialchars($atasan['nip'] ?? '') ?>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <script>
        window.onload = function () {
            window.print();
        };

        window.onafterprint = function () {
            // Optional: close window setelah print
            // window.close();
        };
    </script>
</body>

</html>