<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("super_admin/components/header.php") ?>

    <!-- [PERBAIKAN] Memuat library jQuery di header untuk memastikan semua script berjalan -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Memuat library Chart.js untuk grafik -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- CSS Kustom untuk ikon agar lebih terlihat di warna baru -->
    <style>
        .small-box .icon {
            color: rgba(0, 0, 0, 0.15);
        }

        .small-box h3,
        .small-box p {
            color: #fff;
        }

        .bg-gradient-orange h3,
        .bg-gradient-orange p,
        .bg-gradient-orange .small-box-footer {
            color: #fff !important;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <?php $this->load->view("super_admin/components/navbar.php") ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("super_admin/components/sidebar.php") ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard Atasan</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-teal">
                                <div class="inner">
                                    <h3><?= $pegawai['total_user'] ?? 0 ?></h3>
                                    <p>Anggota Tim</p>
                                </div>
                                <div class="icon"><i class="fas fa-users"></i></div>
                                <a href="<?= base_url(); ?>Atasan/data_tim" class="small-box-footer">
                                    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-orange">
                                <div class="inner">
                                    <h3><?= $cuti_confirm['total_cuti'] ?? 0 ?></h3>
                                    <p>Menunggu Persetujuan Anda</p>
                                </div>
                                <div class="icon"><i class="fas fa-hourglass-start"></i></div>
                                <a href="<?= base_url(); ?>Atasan/manajemen_cuti" class="small-box-footer">Proses Sekarang
                                    <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-blue">
                                <div class="inner">
                                    <h3><?= $cuti_acc['total_cuti'] ?? 0 ?></h3>
                                    <p>Cuti Tim Diterima</p>
                                </div>
                                <div class="icon"><i class="fas fa-check-circle"></i></div>
                                <a href="<?= base_url(); ?>Atasan/manajemen_cuti/diterima" class="small-box-footer">Lihat
                                    Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-gradient-red">
                                <div class="inner">
                                    <h3><?= $cuti_reject['total_cuti'] ?? 0 ?></h3>
                                    <p>Cuti Tim Ditolak</p>
                                </div>
                                <div class="icon"><i class="fas fa-times-circle"></i></div>
                                <a href="<?= base_url(); ?>Atasan/manajemen_cuti/ditolak" class="small-box-footer">Lihat
                                    Detail <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.row -->

                    <!-- Baris untuk Grafik -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-chart-bar mr-1"></i>
                                        Rekap Hari Cuti Tim per Bulan (Tahun Ini)
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <canvas id="monthlyLeaveChart"
                                            style="min-height: 250px; height: 300px; max-height: 300px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">SI Cuti Kemhan</a>.</strong>
        </footer>

    </div>
    <!-- ./wrapper -->

    <?php $this->load->view("super_admin/components/js.php") ?>


   <script>
    $(function () {
        var ctx = document.getElementById('monthlyLeaveChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?= json_encode($chart_labels ?? []); ?>,
                datasets: [{
                    label: 'Total Hari Cuti',
                    data: <?= json_encode($chart_data ?? []); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Hari Cuti: ${Math.round(context.parsed.y)}`; // Pastikan bulat di tooltip
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Jumlah Hari Cuti'
                        },
                        ticks: {
                            stepSize: 1,
                            callback: function (value) {
                                // Pastikan selalu integer
                                if (value % 1 === 0) {
                                    return Math.round(value);
                                }
                                return '';
                            }
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Bulan'
                        }
                    }
                }
            }
        });
    });
</script>

</body>

</html>