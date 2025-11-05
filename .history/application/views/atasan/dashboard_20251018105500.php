<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("admin/components/header.php") ?>
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
                            <h1 class="m-0">Dashboard Super Administrator</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Info boxes -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3><?= $total_pegawai ?></h3>
                                    <p>Total Pegawai</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person"></i>
                                </div>
                                <a href="<?= base_url('Pegawai/view_admin') ?>" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3><?= $stats_cuti_global['diterima'] ?? 0 ?></h3>
                                    <p>Cuti Disetujui</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-checkmark"></i>
                                </div>
                                <a href="<?= base_url('Data_Cuti/view_admin') ?>" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3><?= $stats_cuti_global['menunggu_konfirmasi'] ?? 0 ?></h3>
                                    <p>Menunggu Persetujuan</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-clock"></i>
                                </div>
                                <a href="<?= base_url('Data_Cuti/view_admin') ?>" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3><?= $total_bawahan ?></h3>
                                    <p>Bawahan Langsung</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-people"></i>
                                </div>
                                <a href="<?= base_url('Pegawai/view_admin') ?>" class="small-box-footer">
                                    More info <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Main row -->
                    <div class="row">
                        <!-- Pending Approvals -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-clock mr-1"></i>
                                        Menunggu Persetujuan Atasan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($pending_approvals)): ?>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Pegawai</th>
                                                        <th>Jenis</th>
                                                        <th>Periode</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($pending_approvals as $pending): ?>
                                                    <tr>
                                                        <td><?= htmlspecialchars($pending['nama_lengkap']) ?></td>
                                                        <td><?= htmlspecialchars($pending['nama_cuti']) ?></td>
                                                        <td><?= date('d/m', strtotime($pending['mulai'])) ?> - <?= date('d/m', strtotime($pending['berakhir'])) ?></td>
                                                        <td>
                                                            <span class="badge badge-warning">
                                                                <?= htmlspecialchars($pending['status_cuti']) ?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center">Tidak ada pengajuan yang menunggu persetujuan</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-history mr-1"></i>
                                        Aktivitas Terbaru
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($recent_activities)): ?>
                                        <?php foreach($recent_activities as $activity): ?>
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <div>
                                                <strong><?= htmlspecialchars($activity['nama_lengkap']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars($activity['nama_cuti']) ?></small>
                                            </div>
                                            <div class="text-right">
                                                <span class="badge badge-<?= $activity['color_class'] ?>">
                                                    <?= htmlspecialchars($activity['status_cuti']) ?>
                                                </span>
                                                <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($activity['updated_at'])) ?></small>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted text-center">Belum ada aktivitas terbaru</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php $this->load->view("admin/components/js.php") ?>
</body>
</html>
