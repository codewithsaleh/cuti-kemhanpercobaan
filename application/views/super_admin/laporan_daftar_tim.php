<!DOCTYPE html>
<html lang="id">

<head>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Daftar Anggota Tim</title>
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

        /* Info Box */
        .info-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 10px;
            margin: 15px 0;
            border-radius: 4px;
            font-size: 10px;
        }

        .info-box strong {
            color: #495057;
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
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
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
            border-left: 4px solid #ffc107;
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

        /* Badge untuk jumlah */
        .badge {
            display: inline-block;
            padding: 3px 8px;
            background: #ffc107;
            color: #000;
            border-radius: 10px;
            font-weight: bold;
            font-size: 10px;
        }

        /* Cuti badge */
        .cuti-badge {
            display: inline-block;
            padding: 2px 6px;
            background: #28a745;
            color: white;
            border-radius: 8px;
            font-size: 9px;
            font-weight: bold;
        }

        .cuti-badge-danger {
            background: #dc3545;
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

            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        /* Column widths */
        .col-no {
            width: 5%;
        }

        .col-nama {
            width: 20%;
        }

        .col-nip {
            width: 15%;
        }

        .col-jk {
            width: 8%;
        }

        .col-pangkat {
            width: 15%;
        }

        .col-jabatan {
            width: 15%;
        }

        .col-cuti {
            width: 12%;
        }

        .col-telepon {
            width: 10%;
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
            <h3>DAFTAR ANGGOTA TIM</h3>
        </div>
    </div>

    <!-- Info Atasan -->
    <div class="info-box">
        <strong>Atasan Penanggung Jawab:</strong> <?= htmlspecialchars($atasan['nama_lengkap'] ?? 'N/A') ?> |
        <strong>NIP:</strong> <?= htmlspecialchars($atasan['nip'] ?? 'N/A') ?> |
        <strong>Total Anggota Tim:</strong> <span class="badge"><?= $total_anggota ?> Orang</span> |
        <strong>Tanggal Cetak:</strong> <?= date('d/m/Y H:i') ?>
    </div>

    <!-- Daftar Anggota Tim -->
    <div class="section-header">DATA ANGGOTA TIM</div>

    <table>
        <thead>
            <tr>
                <th class="col-no text-center">NO</th>
                <th class="col-nama text-center">NAMA LENGKAP</th>
                <th class="col-nip text-center">NIP</th>
                <th class="col-jk text-center">JENIS KELAMIN</th>
                <th class="col-pangkat text-center">PANGKAT</th>
                <th class="col-jabatan text-center">JABATAN</th>
                <th class="col-cuti text-center">SISA CUTI</th>
                <th class="col-telepon text-center">NO. TELEPON</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($anggota_tim)): ?>
                <?php $no = 1;
                foreach ($anggota_tim as $anggota):
                    // Format sisa cuti
                    $sisa_cuti = $anggota['sisa_cuti'] ?? 0;
                    $jatah_cuti = $anggota['jatah_cuti'] ?? 12;
                    $cuti_class = $sisa_cuti > 0 ? 'cuti-badge' : 'cuti-badge cuti-badge-danger';
                    ?>
                    <tr>
                        <td class="text-center"><?= $no++ ?></td>
                        <td><?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                        <td><?= htmlspecialchars($anggota['nip']) ?></td>
                        <td class="text-center"><?= htmlspecialchars($anggota['jenis_kelamin'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($anggota['pangkat'] ?? '-') ?></td>
                        <td><?= htmlspecialchars($anggota['jabatan'] ?? '-') ?></td>
                        <td class="text-center">
                            <span class="<?= $cuti_class ?>">
                                <?= $sisa_cuti ?>/<?= $jatah_cuti ?>
                            </span>
                        </td>
                        <td><?= htmlspecialchars($anggota['no_telepon'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 20px;">
                        <strong>Tidak ada anggota tim</strong>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Detail Informasi Tambahan -->
    <?php if (!empty($anggota_tim)): ?>
        <div class="section-header">RINCIAN DATA ANGGOTA TIM</div>

        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">NO</th>
                    <th width="20%" class="text-center">NAMA LENGKAP</th>
                    <th width="15%" class="text-center">PANGKAT/JABATAN</th>
                    <th width="25%" class="text-center">ALAMAT</th>
                    <th width="15%" class="text-center">MASA KERJA</th>
                    <th width="10%" class="text-center">JATAH CUTI</th>
                    <th width="10%" class="text-center">SISA CUTI</th>
                </tr>
            </thead>
            <tbody>
                <?php $no_detail = 1;
                foreach ($anggota_tim as $anggota):
                    // Hitung masa kerja
                    $masa_kerja_hari = $anggota['masa_kerja_hari'] ?? 0;
                    $tahun = floor($masa_kerja_hari / 365);
                    $bulan = floor(($masa_kerja_hari % 365) / 30);
                    $masa_kerja_text = $tahun > 0 ? $tahun . ' tahun' : '';
                    $masa_kerja_text .= $bulan > 0 ? ' ' . $bulan . ' bulan' : '';
                    if (empty($masa_kerja_text))
                        $masa_kerja_text = 'Kurang dari 1 bulan';

                    $sisa_cuti = $anggota['sisa_cuti'] ?? 0;
                    $cuti_class = $sisa_cuti > 0 ? 'cuti-badge' : 'cuti-badge cuti-badge-danger';
                    ?>
                    <tr>
                        <td class="text-center"><?= $no_detail++ ?></td>
                        <td><?= htmlspecialchars($anggota['nama_lengkap']) ?></td>
                        <td class="text-center">
                            <strong><?= htmlspecialchars($anggota['pangkat'] ?? '-') ?></strong><br>
                            <small><?= htmlspecialchars($anggota['jabatan'] ?? '-') ?></small>
                        </td>
                        <td>
                            <?php
                            $alamat = $anggota['alamat'] ?? '-';
                            echo strlen($alamat) > 50 ? substr($alamat, 0, 50) . '...' : $alamat;
                            ?>
                        </td>
                        <td class="text-center"><?= $masa_kerja_text ?></td>
                        <td class="text-center"><?= $anggota['jatah_cuti'] ?? 12 ?> hari</td>
                        <td class="text-center">
                            <span class="<?= $cuti_class ?>">
                                <?= $sisa_cuti ?> hari
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <!-- Statistik Singkat -->
    <?php if (!empty($anggota_tim)): ?>
        <div class="section-header">STATISTIK TIM</div>

        <table>
            <tbody>
                <tr>
                    <td width="25%" style="background:#f8f9fa;"><strong>Total Anggota Tim</strong></td>
                    <td width="25%" class="text-center"><strong><?= $total_anggota ?> Orang</strong></td>
                    <td width="25%" style="background:#f8f9fa;"><strong>Total Sisa Cuti</strong></td>
                    <td width="25%" class="text-center">
                        <strong>
                            <?= array_sum(array_column($anggota_tim, 'sisa_cuti')) ?> hari
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f8f9fa;"><strong>Rata-rata Masa Kerja</strong></td>
                    <td class="text-center">
                        <strong>
                            <?php
                            $total_masa_kerja = array_sum(array_column($anggota_tim, 'masa_kerja_hari'));
                            $rata_masa_kerja = $total_anggota > 0 ? $total_masa_kerja / $total_anggota : 0;
                            $tahun_rata = floor($rata_masa_kerja / 365);
                            $bulan_rata = floor(($rata_masa_kerja % 365) / 30);
                            echo $tahun_rata . ' tahun ' . $bulan_rata . ' bulan';
                            ?>
                        </strong>
                    </td>
                    <td style="background:#f8f9fa;"><strong>Rata-rata Sisa Cuti</strong></td>
                    <td class="text-center">
                        <strong>
                            <?= $total_anggota > 0 ? round(array_sum(array_column($anggota_tim, 'sisa_cuti')) / $total_anggota, 1) : 0 ?>
                            hari
                        </strong>
                    </td>
                </tr>
                <tr>
                    <td style="background:#f8f9fa;"><strong>Anggota dengan No. Telepon</strong></td>
                    <td class="text-center">
                        <strong>
                            <?= count(array_filter($anggota_tim, function ($a) {
                                return !empty($a['no_telepon']); })) ?>
                            Orang
                        </strong>
                    </td>
                    <td style="background:#f8f9fa;"><strong>Anggota Habis Cuti</strong></td>
                    <td class="text-center">
                        <strong>
                            <?= count(array_filter($anggota_tim, function ($a) {
                                return ($a['sisa_cuti'] ?? 0) <= 0; })) ?>
                            Orang
                        </strong>
                    </td>
                </tr>
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