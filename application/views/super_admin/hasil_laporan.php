<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Cuti Tim</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
        }
        .header h4, .header h5 {
            margin: 0;
        }
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
            vertical-align: middle;
            text-align: center;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="container mt-4">
        <div class="header">
            <h4>REKAPITULASI CUTI PEGAWAI</h4>
            <h5>KEMENTERIAN PERTAHANAN RI</h5>
        </div>

        <p><strong>Periode Laporan:</strong> <?= date('d F Y', strtotime($start_date)) ?> s/d <?= date('d F Y', strtotime($end_date)) ?></p>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Pegawai</th>
                    <th>NIP</th>
                    <th>Jenis Cuti</th>
                    <th>Tanggal Cuti</th>
                    <th>Lama Cuti (Hari)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan_cuti)): ?>
                    <?php $no = 1; foreach ($laporan_cuti as $cuti): ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td style="text-align: left;"><?= $cuti['nama_lengkap']; ?></td>
                            <td><?= $cuti['nip']; ?></td>
                            <td><?= $cuti['nama_cuti']; ?></td>
                            <td><?= date('d M Y', strtotime($cuti['mulai'])) ?> s/d <?= date('d M Y', strtotime($cuti['berakhir'])) ?></td>
                            <td><?= $cuti['lama_cuti']; ?></td>
                            <td><?= $cuti['status_cuti']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data cuti pada periode yang dipilih.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p>Jakarta, <?= date('d F Y') ?></p>
                <p>Mengetahui,</p>
                <br><br><br>
                <p><strong>( <?= $this->session->userdata('nama_lengkap'); ?> )</strong></p>
            </div>
        </div>

    </div>

</body>
</html>
