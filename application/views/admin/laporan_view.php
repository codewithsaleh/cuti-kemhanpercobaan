<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("admin/components/header.php") ?>
    <!-- Memuat file CSS yang BENAR untuk kalender Tempus Dominus -->
    <link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view("admin/components/navbar.php") ?>
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Laporan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Dashboard/dashboard_admin')?>">Home</a></li>
                                <li class="breadcrumb-item active">Laporan</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    
                    <div class="card card-filter">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-filter mr-2"></i>Filter Laporan Rekapitulasi</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- [PERUBAHAN] Kolom diperlebar karena tombol Terapkan dihapus -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_mulai">Tanggal Mulai</label>
                                        <div class="input-group date" id="datepicker-mulai" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#datepicker-mulai" id="tanggal_mulai_input" placeholder="YYYY-MM-DD" autocomplete="off"/>
                                            <div class="input-group-append" data-target="#datepicker-mulai" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="tanggal_selesai">Tanggal Selesai</label>
                                        <div class="input-group date" id="datepicker-selesai" data-target-input="nearest">
                                            <input type="text" class="form-control datetimepicker-input" data-target="#datepicker-selesai" id="tanggal_selesai_input" placeholder="YYYY-MM-DD" autocomplete="off"/>
                                            <div class="input-group-append" data-target="#datepicker-selesai" data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <!-- [PERUBAHAN UTAMA] Kartu ini sekarang dibungkus form -->
                            <form action="<?= base_url('Laporan/cetak_rekap_bulanan') ?>" method="post" target="_blank">
                                <!-- Input tersembunyi untuk menampung tanggal yang dipilih -->
                                <input type="hidden" name="tanggal_mulai" id="hidden_tanggal_mulai">
                                <input type="hidden" name="tanggal_selesai" id="hidden_tanggal_selesai">

                                <div class="card card-laporan">
                                    <div class="card-body d-flex justify-content-between align-items-center">
                                        <div class="info">
                                            <i class="fas fa-file-alt icon-laporan text-primary"></i>
                                            <div>
                                                <h5>Rekapitulasi Cuti Bulanan</h5>
                                                <p class="text-muted">Unduh laporan pengajuan cuti berdasarkan filter di atas.</p>
                                            </div>
                                        </div>
                                        <div class="actions">
                                            <!-- Tombol ini sekarang menjadi submit untuk form kartu ini -->
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf mr-1"></i> PDF</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-laporan">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div class="info">
                                        <i class="fas fa-users icon-laporan text-info"></i>
                                        <div>
                                            <h5>Laporan Sisa Cuti Pegawai</h5>
                                            <p class="text-muted">Unduh laporan sisa jatah cuti tahunan untuk semua pegawai.</p>
                                        </div>
                                    </div>
                                    <div class="actions">
                                        <!-- Link ini tidak terpengaruh filter -->
                                        <a href="<?= base_url('Laporan/cetak_sisa_cuti') ?>" target="_blank" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf mr-1"></i> PDF</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Memuat SEMUA file JS yang dibutuhkan dari komponen utama -->
    <?php $this->load->view("admin/components/js.php") ?>
    
    <!-- Skrip KHUSUS untuk Kalender di halaman ini -->
    <script>
        $(document).ready(function() {
            // Inisialisasi Datepicker
            $('#datepicker-mulai').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#datepicker-selesai').datetimepicker({
                format: 'YYYY-MM-DD',
                useCurrent: false
            });

            // Logika untuk memastikan tanggal selesai tidak bisa sebelum tanggal mulai
            $("#datepicker-mulai").on("change.datetimepicker", function (e) {
                $('#datepicker-selesai').datetimepicker('minDate', e.date);
                // [LOGIKA BARU] Update nilai di input tersembunyi saat tanggal berubah
                $('#hidden_tanggal_mulai').val(e.date.format('YYYY-MM-DD'));
            });
            $("#datepicker-selesai").on("change.datetimepicker", function (e) {
                $('#datepicker-mulai').datetimepicker('maxDate', e.date);
                // [LOGIKA BARU] Update nilai di input tersembunyi saat tanggal berubah
                $('#hidden_tanggal_selesai').val(e.date.format('YYYY-MM-DD'));
            });

            // [LOGIKA BARU] Isi nilai awal saat halaman dimuat jika ada
            if ($('#tanggal_mulai_input').val()) {
                 $('#hidden_tanggal_mulai').val($('#tanggal_mulai_input').val());
            }
            if ($('#tanggal_selesai_input').val()) {
                 $('#hidden_tanggal_selesai').val($('#tanggal_selesai_input').val());
            }
        });
    </script>
</body>
</html>

