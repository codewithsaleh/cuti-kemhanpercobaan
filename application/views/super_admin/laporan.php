<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('super_admin/components/header');?>
    <!-- CSS Kustom -->
    <style>
        .report-card-col {
            margin-bottom: 20px; /* Jarak antar baris card */
        }
        .report-card {
            height: 100%; /* Membuat semua card sama tinggi dalam satu baris */
            display: flex;
            flex-direction: column;
        }
        .report-card .card-body {
            text-align: center;
            flex-grow: 1; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            padding-top: 1.5rem; /* Tambah padding atas */
            padding-bottom: 1.5rem; /* Tambah padding bawah */
        }
        .report-card .icon { 
             margin-bottom: 15px;
        }
        .report-card .icon i {
            font-size: 3rem; 
        }
        .report-card h5 {
             margin-bottom: 0.5rem; /* Kurangi margin bawah judul */
        }
         .report-card .card-text {
             margin-bottom: 1.5rem; /* Jarak antara teks dan tombol */
             flex-grow: 1; /* Biarkan teks mengisi ruang */
         }
        .report-card .btn {
            margin-top: auto; 
            align-self: center; 
            width: 80%; 
            max-width: 200px; 
        }
        
        /* Warna Ikon */
        .icon-pdf i { color: #dc3545 !important; } 
        .icon-excel i { color: #28a745 !important; } 
        .icon-stats i { color: #17a2b8 !important; } 
        .icon-list i { color: #ffc107 !important; } 
        .icon-calendar i { color: #6f42c1 !important; } /* Warna ikon kalender (ungu) */

        /* Form Custom Report */
         .custom-report-form {
             justify-content: flex-start; 
             padding: 1rem; /* Padding di dalam body form */
         }
         .custom-report-form .icon {
             margin-top: 0.5rem; /* Jarak ikon dari atas */
         }
         .custom-report-form p.card-text {
              margin-bottom: 1rem; /* Jarak teks deskripsi */
         }
         .custom-report-form label {
             margin-bottom: 0.3rem; /* Kurangi jarak bawah label */
             font-weight: 500;
             text-align: left;
             display: block; 
             font-size: 0.85rem; /* Kecilkan sedikit font label */
         }
         .custom-report-form .form-group {
             margin-bottom: 0.8rem; /* Kurangi jarak antar form group */
             text-align: left; 
         }
          .custom-report-form .form-control-sm {
              font-size: 0.875rem; /* Sesuaikan ukuran font input */
          }
         .custom-report-form .btn {
             width: 100%; 
             max-width: none; 
             margin-top: 1rem; 
         }
         /* Hapus header abu-abu, jadikan bagian dari body */
         .report-card .card-header.bg-light {
             display: none; /* Sembunyikan header abu-abu */
         }
         /* Judul Laporan Custom di dalam body */
          .custom-report-form .custom-title {
              font-size: 1.25rem;
              font-weight: 500;
              margin-bottom: 0.5rem;
              text-align: center;
          }

    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view('super_admin/components/navbar');?>
        <?php $this->load->view('super_admin/components/sidebar');?>
        
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Cetak Laporan</h1> 
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Atasan/dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="content">
                <div class="container-fluid">
                    <div class="row">

                        <!-- Card Laporan Bulanan PDF -->
                        <div class="col-md-6 col-lg-4 report-card-col">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="icon icon-pdf"><i class="fas fa-file-pdf"></i></div>
                                    <h5 class="card-title">Laporan Cuti Tim Bulanan</h5>
                                    <p class="card-text text-muted small">Rekap cuti anggota tim per bulan (tahun ini).</p>
                                    <a href="<?= base_url('Laporan_Atasan/laporan_bulanan'); ?>" target="_blank" class="btn btn-danger"><i class="fas fa-print"></i> Cetak PDF</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Laporan Tahunan CSV -->
                        <div class="col-md-6 col-lg-4 report-card-col">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="icon icon-excel"><i class="fas fa-file-excel"></i></div>
                                    <h5 class="card-title">Laporan Cuti Tim Tahunan</h5>
                                    <p class="card-text text-muted small">Rekap cuti anggota tim tahun <?= date('Y'); ?>.</p>
                                    <a href="<?= base_url('Laporan_Atasan/laporan_tahunan_csv'); ?>" class="btn btn-success"><i class="fas fa-download"></i> Download CSV</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Laporan Statistik -->
                        <div class="col-md-6 col-lg-4 report-card-col">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="icon icon-stats"><i class="fas fa-chart-pie"></i></div>
                                    <h5 class="card-title">Laporan Statistik Tim</h5>
                                    <p class="card-text text-muted small">Analisis penggunaan cuti tim.</p>
                                    <a href="<?= base_url('Laporan_Atasan/laporan_statistik'); ?>" target="_blank" class="btn btn-info"><i class="fas fa-chart-bar"></i> Cetak Statistik</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Daftar Anggota Tim -->
                        <div class="col-md-6 col-lg-4 report-card-col">
                            <div class="card report-card">
                                <div class="card-body">
                                    <div class="icon icon-list"><i class="fas fa-users"></i></div>
                                    <h5 class="card-title">Daftar Anggota Tim</h5>
                                    <p class="card-text text-muted small">Data lengkap anggota tim Anda.</p>
                                    <a href="<?= base_url('Laporan_Atasan/cetak_daftar_tim'); ?>" target="_blank" class="btn btn-warning"><i class="fas fa-list"></i> Cetak Daftar</a>
                                </div>
                            </div>
                        </div>

                        <!-- Card Laporan Custom -->

                    </div> <!-- /.row -->

                </div><!-- /.container-fluid -->
            </section>

    
    <?php $this->load->view('super_admin/components/js');?>
</body>
</html>

