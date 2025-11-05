<!DOCTYPE html>
<html lang="id">

<head>

    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>" type="image/x-icon">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="utf-8">
    <title>Surat Cuti Pegawai - <?= $cuti['nip'] ?></title>
    <style>
        @media print {
            @page {
                margin: 15mm;
                size: A4 portrait;
            }

            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt;
                color: #000;
                margin: 0;
                padding: 0;
                background: white;
                line-height: 1.3;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .action-buttons {
                display: none !important;
            }

            .print-container {
                width: 100%;
                max-width: 100%;
                margin: 0;
                padding: 0;
            }

            .header-kop {
                border-bottom: 3px solid #000;
                padding-bottom: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                justify-content: center;
                page-break-after: avoid;
            }

            .logo {
                width: 65px;
                height: 65px;
                margin-right: 15px;
                object-fit: contain;
            }

            .kop-text {
                text-align: center;
            }

            .kop-text h2 {
                margin: 0;
                padding: 0;
                font-size: 18pt;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.1;
            }

            .kop-text p {
                margin: 5px 0 0 0;
                font-size: 11pt;
            }

            .tanggal-surat {
                text-align: right;
                margin-bottom: 25px;
                font-size: 12pt;
            }

            .tujuan-surat {
                text-align: right;
                margin-bottom: 25px;
                font-size: 12pt;
                line-height: 1.4;
            }

            .tujuan-surat div {
                text-align: left;
                display: inline-block;
            }

            .isi-surat {
                text-align: justify;
                margin-bottom: 10px;
                font-size: 12pt;
                text-indent: 0px;
                line-height: 1.3;
            }

            .data-diri {
                margin-left: 50px;
                margin-bottom: 15px;
                font-size: 12pt;
                line-height: 1.2;
            }

            .data-diri p {
                margin: 4px 0;
                display: flex;
            }

            .data-label {
                width: 150px;
                flex-shrink: 0;
            }

            .data-value {
                flex-grow: 1;
            }

            .alasan {
                margin-bottom: 15px;
            }

            .penutup {
                margin-bottom: 40px;
            }

            .tanda-tangan {
                text-align: right;
                margin-top: 60px;
                line-height: 1.2;
            }

            .tanda-tangan div {
                text-align: left;
                display: inline-block;
            }

            .nama-pengaju {
                margin-top: 50px;
                font-weight: bold;
                font-size: 12pt;
            }

            .salam-pembuka {
                text-indent: 0 !important;
                margin-bottom: 10px;
            }
        }

        @media screen {
            body {
                font-family: 'Times New Roman', Times, serif;
                font-size: 12pt;
                color: #333;
                margin: 0;
                padding: 20px;
                background: #f5f5f5;
                line-height: 1.3;
            }

            .print-container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
                max-width: 210mm;
                min-height: 280mm;
                margin: 0 auto;
                box-sizing: border-box;
            }

            .action-buttons {
                text-align: center;
                margin: 20px 0;
                padding: 15px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }

            .btn-print {
                background: #e74c3c;
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
                margin: 0 10px;
                transition: all 0.3s ease;
            }

            .btn-print:hover {
                background: #c0392b;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            }

            .btn-back {
                background: #95a5a6;
                color: white;
                border: none;
                padding: 10px 25px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 14px;
                font-weight: bold;
                margin: 0 10px;
                transition: all 0.3s ease;
            }

            .btn-back:hover {
                background: #7f8c8d;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
            }

            .header-kop {
                border-bottom: 3px solid #000;
                padding-bottom: 10px;
                margin-bottom: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .logo {
                width: 70px;
                height: 70px;
                margin-right: 15px;
                object-fit: contain;
            }

            .kop-text h2 {
                margin: 0;
                padding: 0;
                font-size: 18pt;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.0;
            }

            .kop-text p {
                margin: 5px 0 0 0;
                font-size: 12pt;
            }

            .tanggal-surat {
                text-align: right;
                margin-bottom: 25px;
                font-size: 12pt;
            }

            .tujuan-surat {
                text-align: right;
                margin-bottom: 25px;
                font-size: 12pt;
                line-height: 1.4;
            }

            .tujuan-surat div {
                text-align: left;
                display: inline-block;
            }

            .isi-surat {
                text-align: justify;
                margin-bottom: 10px;
                font-size: 12pt;
                text-indent: 0px;
                line-height: 1.3;
            }

            .data-diri {
                margin-left: 50px;
                margin-bottom: 15px;
                font-size: 12pt;
                line-height: 1.2;
            }

            .data-diri p {
                margin: 4px 0;
                display: flex;
            }

            .data-label {
                width: 150px;
                flex-shrink: 0;
            }

            .data-value {
                flex-grow: 1;
            }

            .alasan {
                margin-bottom: 15px;
            }

            .penutup {
                margin-bottom: 40px;
            }

            .tanda-tangan {
                text-align: right;
                margin-top: 60px;
                line-height: 1.2;
            }

            .tanda-tangan div {
                text-align: left;
                display: inline-block;
            }

            .nama-pengaju {
                margin-top: 50px;
                font-weight: bold;
                font-size: 12pt;
            }

            .salam-pembuka {
                text-indent: 0 !important;
                margin-bottom: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <!-- Action Buttons -->
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
                <h2>kementerian pertahanan </h2>
                <p>FORMULIR PERMINTAAN DAN PEMBERIAN CUTI</p>
            </div>
        </div>

        <!-- Tanggal Surat -->
        <div class="tanggal-surat">
            Jakarta, <?= $tanggal_surat ?>
        </div>

        <!-- Tujuan Surat -->
        <div class="tujuan-surat">
            <div>
                Kepada Yth.<br>
                Pejabat yang berwenang memberikan cuti<br>
                di -<br>
                &nbsp;&nbsp;&nbsp;&nbsp; Tempat
            </div>
        </div>

        <!-- Salam Pembuka -->
        <div class="isi-surat salam-pembuka">
            Dengan Hormat,
        </div>

        <!-- Isi Surat -->
        <div class="isi-surat">
            Yang bertanda tangan di bawah ini :
        </div>

        <div class="data-diri">
            <p>
                <span class="data-label">Nama</span>
                <span class="data-value">: <?= $cuti['nama_lengkap'] ?></span>
            </p>
            <p>
                <span class="data-label">NIP/NRP</span>
                <span class="data-value">: <?= $cuti['nip'] ?></span>
            </p>
            <p>
                <span class="data-label">Pangkat/Gol.Ruang</span>
                <span class="data-value">: <?= $cuti['pangkat'] ?></span>
            </p>
            <p>
                <span class="data-label">Jabatan</span>
                <span class="data-value">: <?= $cuti['jabatan'] ?></span>
            </p>
        </div>

        <div class="isi-surat">
            Dengan ini mengajukan permintaan <?= $cuti['nama_cuti'] ?> untuk tahun
            <?= $tahun_pengajuan ?> selama <?= $cuti['lama_cuti'] ?>( <?= terbilang($cuti['lama_cuti']) ?> ) hari kerja,
            terhitung mulai tanggal
            <?= $tanggal_mulai ?> sampai dengan <?= $tanggal_berakhir ?>.
        </div>

        <!-- Alasan -->
        <div class="alasan">
            <div class="isi-surat">
                Adapun alasan pengambilan keputusan cuti adalah sebagai berikut :<br>
                <?= $cuti['alasan'] ?>
            </div>
        </div>

        <!-- Salam Penutup -->
        <div class="penutup">
            <div class="isi-surat">
                Demikian permintaan ini saya buat untuk dapat dipertimbangkan sebagaimana mestinya.
            </div>
        </div>

        <!-- Tanda Tangan -->
        <div class="tanda-tangan">
            <div>
                Hormat Saya<br><br><br>
                <div class="nama-pengaju">
                    <?= $cuti['nama_lengkap'] ?><br>
                    NIP. <?= $cuti['nip'] ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const actionButtons = document.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.scrollIntoView({ behavior: 'smooth' });
            }
        });

        window.onafterprint = function () {
            console.log('Print completed');
        };
    </script>
</body>

</html>