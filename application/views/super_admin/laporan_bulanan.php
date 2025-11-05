<!DOCTYPE html>
<html lang="id">

<head>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Laporan Cuti Tim Bulanan</title>

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
        }

        /* Ukuran A4 */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                width: 210mm;
                height: 297mm;
                margin: 0 auto;
            }

            .no-print {
                display: none !important;
            }
        }

        /* Kop Surat */
        .header-kop {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #000;
        }

        .logo {
            width: 80px;
            height: 80px;
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

        .kop-text p {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
        }

        /* Judul Laporan */
        .judul-laporan {
            text-align: center;
            margin: 25px 0;
        }

        .judul-laporan h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .judul-laporan .periode {
            font-size: 14px;
            font-weight: bold;
        }

        /* Tabel */
        .table-container {
            margin: 20px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th {
            background-color: #f8f9fa;
            border: 1px solid #000;
            padding: 8px 6px;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
        }

        td {
            border: 1px solid #000;
            padding: 6px 4px;
            font-size: 10px;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
        }

        /* Status Badge */
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }

        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-disetujui {
            background-color: #d4edda;
            color: #155724;
        }

        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-review {
            background-color: #cce7ff;
            color: #004085;
        }

        /* Footer dan Tanda Tangan */
        .footer {
            margin-top: 40px;
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

        /* Print Controls */
        .print-controls {
            background: #f8f9fa;
            padding: 15px;
            text-align: center;
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }

        .btn-print {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin: 0 5px;
        }

        .btn-print:hover {
            background: #c82333;
        }

        .btn-back {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
            margin: 0 5px;
        }

        .btn-back:hover {
            background: #5a6268;
        }

        /* Page Break untuk Print */
        @media print {
            .page-break {
                page-break-after: always;
            }
        }

        /* Responsive untuk layar */
        @media screen {
            body {
                background: #f8f9fa;
                padding: 20px;
            }

            .table-container {
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
</head>

<body>
    <!-- Controls untuk Screen -->
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
            <h2>kementerian pertahanan <br> republik indonesia</h2>
        </div>
    </div>

    <!-- Judul Laporan -->
    <div class="judul-laporan">
        <h1>Laporan Cuti Tim Bulanan</h1>
        <div class="periode">Periode Tahun <?= $tahun ?></div>
    </div>

    <!-- Tabel Laporan -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 20%">Nama Lengkap</th>
                    <th style="width: 15%">NIP/NRP</th>
                    <th style="width: 15%">Jenis Cuti</th>
                    <th style="width: 12%">Tanggal Mulai</th>
                    <th style="width: 12%">Tanggal Berakhir</th>
                    <th style="width: 8%">Lama Cuti</th>
                    <th style="width: 13%">Status Cuti</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cuti_tim)): ?>
                    <?php $no = 1;
                    foreach ($cuti_tim as $cuti): ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($cuti['nama_lengkap']) ?></td>
                            <td class="text-center"><?= htmlspecialchars($cuti['nip']) ?></td>
                            <td><?= htmlspecialchars($cuti['nama_cuti']) ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($cuti['mulai'])) ?></td>
                            <td class="text-center"><?= date('d/m/Y', strtotime($cuti['berakhir'])) ?></td>
                            <td class="text-center"><?= $cuti['lama_cuti'] ?> hari</td>
                            <td class="text-center">
                                <?php
                                $status_class = '';
                                switch ($cuti['id_status_cuti']) {
                                    case 1:
                                        $status_class = 'status-menunggu';
                                        break;
                                    case 2:
                                        $status_class = 'status-disetujui';
                                        break;
                                    case 3:
                                        $status_class = 'status-ditolak';
                                        break;
                                    case 4:
                                        $status_class = 'status-review';
                                        break;
                                    default:
                                        $status_class = 'status-menunggu';
                                }
                                ?>
                                <span class="status-badge <?= $status_class ?>">
                                    <?= $cuti['status_cuti'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 20px;">
                            Tidak ada data cuti tim untuk tahun <?= $tahun ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


    <script>
        // Auto print ketika halaman loaded (opsional)
        window.onload = function () {
            // Uncomment baris berikut jika ingin auto print
            // window.print();
        };

        // Handle sebelum print
        window.addEventListener('beforeprint', function () {
            console.log('Mempersiapkan cetakan...');
        });

        // Handle setelah print
        window.addEventListener('afterprint', function () {
            console.log('Cetakan selesai');
        });
    </script>
</body>

</html>