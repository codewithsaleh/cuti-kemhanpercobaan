<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Cuti Saya</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item active">Data Cuti</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <!-- Flash Messages -->
                    <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('success') ?>
                    </div>
                    <?php endif; ?>

                    <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Riwayat Pengajuan Cuti</h3>
                            <div class="card-tools">
                                <a href="<?= base_url('Cuti/add_cuti_pegawai') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Ajukan Cuti Baru
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered table-striped" id="example1">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Jenis Cuti</th>
                                        <th>Periode</th>
                                        <th>Durasi</th>
                                        <th>Tanggal Diajukan</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cuti)): ?>
                                        <?php $no = 1; foreach($cuti as $c): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <span class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <?= date('d/m/Y', strtotime($c['mulai'])) ?> - 
                                                <?= date('d/m/Y', strtotime($c['berakhir'])) ?>
                                            </td>
                                            <td>
                                                <span class="badge badge-secondary"><?= $c['jumlah_hari'] ?> hari</span>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($c['tgl_diajukan'])) ?></td>
                                            <td>
                                                <?php 
                                                $color = $c['color_class'] ?? 'secondary';
                                                switch($c['id_status_cuti']) {
                                                    case 1: $color = 'warning'; break;
                                                    case 2: $color = 'success'; break;
                                                    case 3: $color = 'danger'; break;
                                                    case 4: $color = 'info'; break;
                                                }
                                                ?>
                                                <span class="badge badge-<?= $color ?>">
                                                    <?= htmlspecialchars($c['status_cuti'] ?? 'Unknown') ?>
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-info" data-toggle="modal" 
                                                    data-target="#detail_<?= $c['id_cuti'] ?>" title="Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center">
                                                <i class="fas fa-info-circle text-muted fa-2x mb-3"></i>
                                                <br>Belum ada pengajuan cuti
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <!-- Modal Detail -->
    <?php if (!empty($cuti)): ?>
        <?php foreach($cuti as $c): ?>
        <div class="modal fade" id="detail_<?= $c['id_cuti'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Pengajuan Cuti</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Jenis Cuti:</th>
                                <td><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Periode:</th>
                                <td><?= date('d F Y', strtotime($c['mulai'])) ?> - <?= date('d F Y', strtotime($c['berakhir'])) ?></td>
                            </tr>
                            <tr>
                                <th>Durasi:</th>
                                <td><?= $c['jumlah_hari'] ?> hari</td>
                            </tr>
                            <tr>
                                <th>Alasan:</th>
                                <td><?= htmlspecialchars($c['alasan']) ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-<?= $color ?>">
                                        <?= htmlspecialchars($c['status_cuti'] ?? 'Unknown') ?>
                                    </span>
                                </td>
                            </tr>
                            <?php if (!empty($c['alasan_verifikasi'])): ?>
                            <tr>
                                <th>Catatan:</th>
                                <td><?= htmlspecialchars($c['alasan_verifikasi']) ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->load->view("pegawai/components/js.php") ?>
    
    <script>
    $(document).ready(function() {
        $('#example1').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                "paginate": {
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    });
    </script>
</body>
</html>
