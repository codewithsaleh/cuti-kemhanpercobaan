<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("admin/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php $this->load->view("admin/components/navbar.php") ?>

        <!-- Main Sidebar Container -->
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <!-- Header Halaman sengaja dikosongkan untuk diganti Header Sambutan -->
                </div>
            </div>


            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- [BARU] Header Sambutan Profesional -->
                    <div class="dashboard-welcome-header">
                        <h1>Selamat Datang Kembali, <?= $this->session->userdata('username'); ?>!</h1>
                        <p>Berikut adalah ringkasan aktivitas di sistem saat ini.</p>
                    </div>

                    <div class="row">
                        <!-- Kolom untuk Chart -->
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-pie mr-1"></i>
                                        Ringkasan Status Cuti
                                    </h3>
                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="chart-container">
                                        <canvas id="cutiStatusChart"></canvas>
                                    </div>
                                </div><!-- /.card-body -->
                            </div>
                        </div>

                        <!-- Kolom untuk Stat Box -->
                        <div class="col-lg-5">
                            <div class="row">
                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-purple">
                                        <div class="inner">
                                            <h3><?= $cuti['total_cuti'] ?? 0 ?></h3>
                                            <p>Total Pengajuan</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Cuti/view_admin/semua"
                                            class="small-box-footer">Detail
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-teal">
                                        <div class="inner">
                                            <h3><?= $pegawai['total_user'] ?? 0 ?></h3>
                                            <!-- [MODIFIKASI] Mengganti nama kotak menjadi Pengguna -->
                                            <p>Total Pengguna</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Pegawai/view_admin" class="small-box-footer">Detail
                                            <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-blue">
                                        <div class="inner">
                                            <h3><?= $cuti_acc['total_cuti'] ?? 0 ?></h3>
                                            <p>Diterima</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Cuti/view_admin/diterima"
                                            class="small-box-footer">Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-red">
                                        <div class="inner">
                                            <h3><?= $cuti_reject['total_cuti'] ?? 0 ?></h3>
                                            <p>Ditolak</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Cuti/view_admin/ditolak"
                                            class="small-box-footer">Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-orange">
                                        <div class="inner">
                                            <h3><?= $cuti_confirm['total_cuti'] ?? 0 ?></h3>
                                            <p>Menunggu</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Cuti/view_admin/menunggu"
                                            class="small-box-footer">Detail <i
                                                class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-6">
                                    <div class="small-box bg-gradient-secondary">
                                        <div class="inner">
                                            <h3><?= $cuti_arsip['total_cuti'] ?? 0 ?></h3>
                                            <p>Diarsipkan</p>
                                        </div>
                                        <a href="<?= base_url(); ?>Cuti/view_admin/arsip" class="small-box-footer">
                                            Detail <i class="fas fa-arrow-circle-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>

        </div>

    </div>


    <?php $this->load->view("admin/components/js.php") ?>

    <!-- Skrip khusus untuk dashboard -->
    <script src="<?= base_url(); ?>assets/admin_lte/plugins/chart.js/Chart.min.js"></script>

    <!-- JS untuk konten dashboard -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Ambil data dari PHP
            const cutiDiterima = <?= $cuti_acc['total_cuti'] ?? 0 ?>;
            const cutiDitolak = <?= $cuti_reject['total_cuti'] ?? 0 ?>;
            const cutiMenunggu = <?= $cuti_confirm['total_cuti'] ?? 0 ?>;

            // Konfigurasi Chart
            const ctx = document.getElementById('cutiStatusChart').getContext('2d');
            const cutiStatusChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Diterima', 'Ditolak', 'Menunggu Konfirmasi'],
                    datasets: [{
                        label: 'Status Cuti',
                        data: [cutiDiterima, cutiDitolak, cutiMenunggu],
                        backgroundColor: [
                            'rgba(40, 167, 69, 0.8)',   // Hijau
                            'rgba(220, 53, 69, 0.8)',   // Merah
                            'rgba(255, 193, 7, 0.8)'    // Kuning
                        ],
                        borderColor: [
                            '#ffffff',
                            '#ffffff',
                            '#ffffff'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#697586',
                                padding: 20,
                                font: {
                                    family: "'Poppins', sans-serif",
                                    size: 14
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>

</body>

</html>