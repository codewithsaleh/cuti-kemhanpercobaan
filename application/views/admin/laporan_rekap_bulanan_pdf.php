<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Rekapitulasi Cuti Pegawai</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/login/images/Logo_kemhan.png') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <style>
        @media print {
            @page {
                margin: 15mm;
                size: A4 portrait;
            }
            
            body { 
                font-family: 'Times New Roman', Times, serif;
                font-size: 12px; 
                color: #000;
                margin: 0;
                padding: 0;
                background: white;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            .action-buttons {
                display: none !important;
            }
            
            .header-kop {
                border-bottom: 2px solid #000;
                padding-bottom: 10px;
                margin-bottom: 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                page-break-after: avoid;
            }
            
            .logo {
                width: 70px;
                height: 70px;
                margin-right: 15px;
                object-fit: contain;
            }
            
            .kop-text {
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
                page-break-after: avoid;
            }
            
            .judul-laporan h3 {
                margin: 0;
                padding: 0;
                font-size: 16px;
                text-transform: uppercase;
                text-decoration: underline;
                font-weight: bold;
            }
            
            .judul-laporan p {
                margin: 3px 0;
                font-size: 11px;
            }
            
            .periode-info {
                text-align: center;
                margin: 10px 0 15px 0;
                font-size: 12px;
                font-weight: bold;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 10px;
                page-break-inside: auto;
            }
            
            th, td {
                border: 1px solid #000;
                padding: 6px;
                text-align: left;
            }
            
            th {
                background-color: #f2f2f2 !important;
                font-weight: bold;
                text-align: center;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            
            .no { width: 5%; text-align: center; }
            .nama { width: 25%; }
            .nip { width: 15%; text-align: center; }
            .jenis { width: 15%; }
            .mulai { width: 12%; text-align: center; }
            .berakhir { width: 12%; text-align: center; }
            .lama { width: 8%; text-align: center; }
            .status { width: 8%; text-align: center; }
            
            .footer-info {
                margin-top: 20px;
                text-align: center;
                font-size: 10px;
                color: #666;
            }
        }

        @media screen {
            body { 
                font-family: 'Arial', sans-serif;
                font-size: 12px; 
                color: #333;
                margin: 20px;
                padding: 0;
                background: #f5f5f5;
            }
            
            .print-container {
                background: white;
                padding: 30px;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0,0,0,0.1);
                max-width: 1000px;
                margin: 0 auto;
            }
            
            .action-buttons {
                text-align: center;
                margin: 30px 0;
                padding: 20px;
                background: #f8f9fa;
                border-radius: 8px;
                border: 1px solid #dee2e6;
            }
            
            .btn-print {
                background: #e74c3c;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin: 0 10px;
                transition: all 0.3s ease;
            }
            
            .btn-print:hover {
                background: #c0392b;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
            }
            
            .btn-close {
                background: #95a5a6;
                color: white;
                border: none;
                padding: 12px 30px;
                border-radius: 6px;
                cursor: pointer;
                font-size: 16px;
                font-weight: bold;
                margin: 0 10px;
                transition: all 0.3s ease;
            }
            
            .btn-close:hover {
                background: #7f8c8d;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(149, 165, 166, 0.3);
            }
            
            .header-kop {
                border-bottom: 2px solid #000;
                padding-bottom: 15px;
                margin-bottom: 25px;
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }
            
            .logo {
                width: 80px;
                height: 80px;
                margin-right: 20px;
                object-fit: contain;
            }
            
            .kop-text h2 {
                margin: 0;
                padding: 0;
                font-size: 18px;
                font-weight: bold;
                text-transform: uppercase;
                line-height: 1.3;
                font-family: 'Times New Roman', Times, serif;
            }
            
            .judul-laporan {
                text-align: center;
                margin: 25px 0;
            }
            
            .judul-laporan h3 {
                margin: 0;
                padding: 0;
                font-size: 22px;
                text-transform: uppercase;
                text-decoration: underline;
                font-weight: bold;
                color: #2c3e50;
                font-family: 'Times New Roman', Times, serif;
            }
            
            .judul-laporan p {
                margin: 8px 0;
                font-size: 14px;
                color: #7f8c8d;
                font-family: 'Times New Roman', Times, serif;
            }
            
            .periode-info {
                text-align: center;
                margin: 15px 0;
                font-size: 14px;
                font-weight: bold;
                color: #34495e;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 20px;
                background: white;
            }
            
            th, td {
                border: 1px solid #dee2e6;
                padding: 12px;
                text-align: left;
            }
            
            th {
                background-color: #34495e !important;
                color: white;
                font-weight: bold;
                text-align: center;
            }
            
            .no { width: 5%; text-align: center; }
            .nama { width: 25%; }
            .nip { width: 15%; text-align: center; }
            .jenis { width: 15%;text-align: center }
            .mulai { width: 12%; text-align: center; }
            .berakhir { width: 12%; text-align: center; }
            .lama { width: 8%; text-align: center; }
            .status { width: 8%; text-align: center; }
            
            tr:hover {
                background-color: #f8f9fa;
            }
            
            .footer-info {
                margin-top: 30px;
                text-align: center;
                font-size: 12px;
                color: #7f8c8d;
            }
        }
    </style>
</head>
<body>
    <div class="print-container">
        <!-- Action Buttons -->
        <div class="action-buttons">
            <div class="text-center my-4 d-print-none">
    <button type="button" class="btn btn-primary" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i> Cetak Laporan
    </button>
    <button type="button" class="btn btn-secondary ms-2" onclick="window.close()">
        <i class="fa-solid fa-circle-xmark me-2"></i> Tutup
    </button>
</div>
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
            <h3>rekapitulasi cuti bulanan pegawai</h3>
            <p>Sistem Informasi Cuti Pegawai Kementerian Pertahanan</p>
        </div>

        <!-- Periode -->
        <div class="periode-info">
            <strong>PERIODE:</strong> 
            <?= date('d F Y', strtotime($start_date)) ?> s/d <?= date('d F Y', strtotime($end_date)) ?>
        </div>

        <!-- Tabel Data -->
        <table>
            <thead>
                <tr>
                    <th class="no">No</th>
                    <th class="nama">Nama Lengkap</th>
                    <th class="nip">NIP/NRP</th>
                    <th class="jenis">Jenis Cuti</th>
                    <th class="mulai">Tanggal Mulai</th>
                    <th class="berakhir">Tanggal Berakhir</th>
                    <th class="lama">Lama</th>
                    <th class="status">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($laporan_cuti)): ?>
                        <?php $no = 1;
                        foreach ($laporan_cuti as $cuti): ?>
                                <tr>
                                    <td class="no"><?= $no++; ?></td>
                                    <td class="nama"><?= htmlspecialchars($cuti['nama_lengkap']); ?></td>
                                    <td class="nip"><?= htmlspecialchars($cuti['nip']); ?></td>
                                    <td class="jenis"><?= htmlspecialchars($cuti['nama_cuti']); ?></td>
                                    <td class="mulai"><?= date('d/m/Y', strtotime($cuti['mulai'])); ?></td>
                                    <td class="berakhir"><?= date('d/m/Y', strtotime($cuti['berakhir'])); ?></td>
                                    <td class="lama"><?= $cuti['jumlah_hari'] ?? $cuti['lama_cuti'] ?> Hari</td>
                                    <td class="status"><?= $cuti['status_cuti'] ?? 'Pending' ?></td>
                                </tr>
                        <?php endforeach; ?>
                <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align: center;">Tidak ada data cuti pada periode yang dipilih.</td>
                        </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Footer Info -->
        <div class="footer-info">
            <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?> oleh Sistem Informasi Cuti Pegawai</p>
        </div>
    </div>

    <script>
        // Auto focus untuk better UX
        document.addEventListener('DOMContentLoaded', function() {
            // Optional: Scroll ke action buttons
            const actionButtons = document.querySelector('.action-buttons');
            if (actionButtons) {
                actionButtons.scrollIntoView({ behavior: 'smooth' });
            }
        });

        // After print event
        window.onafterprint = function() {
            // Bisa tambahkan notifikasi atau action setelah print
            console.log('Print completed');
        };

        $(document).ready(function() {
    // Set tanggal default ke bulan berjalan saat halaman load
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    
    // Format tanggal ke YYYY-MM-DD
    const formatDate = (date) => {
        return date.toISOString().split('T')[0];
    };

    // Set nilai default untuk datepicker
    $('#datepicker-mulai').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: firstDay
    });
    
    $('#datepicker-selesai').datetimepicker({
        format: 'YYYY-MM-DD',
        defaultDate: lastDay,
        useCurrent: false
    });

    // Set nilai input field
    $('#tanggal_mulai_input').val(formatDate(firstDay));
    $('#tanggal_selesai_input').val(formatDate(lastDay));
    
    // Set nilai hidden field
    $('#hidden_tanggal_mulai').val(formatDate(firstDay));
    $('#hidden_tanggal_selesai').val(formatDate(lastDay));

    // Logika untuk memastikan tanggal selesai tidak bisa sebelum tanggal mulai
    $("#datepicker-mulai").on("change.datetimepicker", function (e) {
        $('#datepicker-selesai').datetimepicker('minDate', e.date);
        $('#hidden_tanggal_mulai').val(e.date.format('YYYY-MM-DD'));
    });
    
    $("#datepicker-selesai").on("change.datetimepicker", function (e) {
        $('#datepicker-mulai').datetimepicker('maxDate', e.date);
        $('#hidden_tanggal_selesai').val(e.date.format('YYYY-MM-DD'));
    });
});
    </script>
</body>
</html>