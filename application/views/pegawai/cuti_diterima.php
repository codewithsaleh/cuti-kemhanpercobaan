<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
    <style>
        #cutiTable thead th {
            background-color: #4e73df;
            color: white;
            font-weight: 600;
        }

        .modal-header {
            background-color: #4e73df;
            color: white;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <script>
            swal({
                title: "Success!",
                text: "<?= $this->session->flashdata('success') ?>",
                icon: "success"
            });
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <script>
            swal({
                title: "Error!",
                text: "<?= $this->session->flashdata('error') ?>",
                icon: "error"
            });
        </script>
    <?php endif; ?>

    <div class="wrapper">
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Riwayat Pengajuan Cuti</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a
                                        href="<?= base_url('Dashboard/dashboard_pegawai') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Data Cuti</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-check-circle mr-2 text-success"></i>
                                Daftar Cuti yang Diterima
                            </h3>
                            <div class="card-tools">
                                <a href="<?= base_url('Cuti/add_cuti_pegawai') ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> Ajukan Cuti Baru
                                </a>
                                <a href="<?= base_url('Cuti/view_pegawai') ?>" class="btn btn-secondary btn-sm ml-1">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="cutiDiterimaTable" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th style="width: 5%;">No</th>
                                            <th style="width: 15%;">Jenis Cuti</th>
                                            <th style="width: 20%;">Perihal</th>
                                            <th style="width: 18%;">Periode Cuti</th>
                                            <th style="width: 12%;">Lama Cuti</th>
                                            <th style="width: 15%;">Tujuan</th>
                                            <th style="width: 10%;">Status</th>
                                            <th style="width: 15%;">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($cuti_diterima) && is_array($cuti_diterima)): ?>
                                            <?php $no = 1;
                                            foreach ($cuti_diterima as $c): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <span
                                                            class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $perihal = $c['perihal_cuti'] ?? 'N/A';
                                                        echo htmlspecialchars(strlen($perihal) > 30 ? substr($perihal, 0, 30) . '...' : $perihal);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <small><i class="fas fa-calendar-check text-primary"></i>
                                                            <?= date('d/m/Y', strtotime($c['mulai'])) ?></small>
                                                        <br><small class="text-muted">s/d
                                                            <?= date('d/m/Y', strtotime($c['berakhir'])) ?></small>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-clock"></i> <?= $c['jumlah_hari'] ?? 0 ?> hari
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $tujuan = $c['tujuan'] ?? 'Tidak ada';
                                                        echo htmlspecialchars(strlen($tujuan) > 25 ? substr($tujuan, 0, 25) . '...' : $tujuan);
                                                        ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-success">
                                                            <i class="fas fa-check"></i> Disetujui
                                                        </span>
                                                        <?php if (!empty($c['approved_at'])): ?>
                                                            <br><small
                                                                class="text-muted"><?= date('d/m/Y', strtotime($c['approved_at'])) ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-info" data-toggle="modal"
                                                                data-target="#detail_<?= $c['id_cuti'] ?>" title="Lihat Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </button>

                                                            <!-- Cetak Surat -->
                                                            <a href="<?= base_url('Surat/cetak_surat/' . $c['id_cuti']) ?>"
                                                                class="btn btn-sm btn-success" target="_blank"
                                                                title="Cetak Surat">
                                                                <i class="fas fa-print"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Modal Detail -->
                                                <div class="modal fade" id="detail_<?= $c['id_cuti'] ?>">
                                                    <div class="modal-dialog modal-lg">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h4 class="modal-title">Detail Pengajuan Cuti</h4>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <strong>Jenis Cuti:</strong><br>
                                                                        <?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?><br><br>

                                                                        <strong>Perihal:</strong><br>
                                                                        <?= htmlspecialchars($c['perihal_cuti'] ?? 'N/A') ?><br><br>

                                                                        <strong>Tanggal Pengajuan:</strong><br>
                                                                        <?= date('d/m/Y', strtotime($c['tgl_diajukan'])) ?><br><br>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <strong>Periode Cuti:</strong><br>
                                                                        <?= date('d/m/Y', strtotime($c['mulai'])) ?> -
                                                                        <?= date('d/m/Y', strtotime($c['berakhir'])) ?><br>
                                                                        <small
                                                                            class="text-success">(<?= $c['jumlah_hari'] ?? 0 ?>
                                                                            hari)</small><br><br>

                                                                        <strong>Status:</strong><br>
                                                                        <span
                                                                            class="badge badge-success">Disetujui</span><br><br>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-12">
                                                                        <strong>Alasan Cuti:</strong><br>
                                                                        <?= nl2br(htmlspecialchars($c['alasan'] ?? 'Tidak ada alasan')) ?><br><br>

                                                                        <strong>Tujuan:</strong><br>
                                                                        <?= htmlspecialchars($c['tujuan'] ?? 'Tidak ada') ?><br><br>

                                                                        <strong>Berkendaraan:</strong><br>
                                                                        <?= htmlspecialchars($c['berkendaraan'] ?? 'Tidak ada') ?><br><br>

                                                                        <strong>Pengikut:</strong><br>
                                                                        <?= htmlspecialchars($c['pengikut'] ?? 'Tidak ada') ?><br><br>

                                                                        <strong>Keperluan:</strong><br>
                                                                        <?= nl2br(htmlspecialchars($c['keperluan'] ?? 'Tidak ada')) ?>
                                                                    </div>
                                                                </div>

                                                                <!-- TAMBAHAN: Catatan Persetujuan Admin -->
                                                                <?php if (!empty($c['alasan_verifikasi'])): ?>
                                                                    <hr>
                                                                    <div class="row bg-success p-2">
                                                                        <div class="col-12 ">
                                                                            <strong class="text-light">
                                                                                <i class="fas fa-check-circle mr-1"></i>Catatan
                                                                                Persetujuan Admin:
                                                                            </strong><br>
                                                                            <?= nl2br(htmlspecialchars($c['alasan_verifikasi'])) ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>

                                                                <?php if (!empty($c['approved_at'])): ?>
                                                                    <hr>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <strong>Disetujui Pada:</strong><br>
                                                                            <?= date('d/m/Y H:i', strtotime($c['approved_at'])) ?>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <i class="fas fa-check-circle text-muted fa-2x mb-3"></i>
                                                    <br><span class="text-muted">Belum ada cuti yang diterima</span>
                                                    <br>
                                                    <a href="<?= base_url('Cuti/add_cuti_pegawai') ?>"
                                                        class="btn btn-primary btn-sm mt-2">
                                                        <i class="fas fa-plus"></i> Ajukan Cuti Baru
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <script>
                $(document).ready(function () {
                    $('#cutiDiterimaTable').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "order": [[3, 'desc']] // Urutkan berdasarkan tanggal mulai descending
                    });
                });
            </script>
        </div>
    </div>

    <!-- Modal Detail -->
    <?php if (!empty($cuti) && is_array($cuti)): ?>
        <?php foreach ($cuti as $c): ?>
            <div class="modal fade" id="detail_<?= $c['id_cuti'] ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <i class="fas fa-info-circle"></i> Detail Pengajuan Cuti
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <div class="bg-primary rounded p-3 mb-3 text-white">
                                        <i class="fas fa-user fa-3x mb-2"></i>
                                        <h5><?= htmlspecialchars($c['nama_lengkap'] ?? 'N/A') ?></h5>
                                        <p class="mb-0"><?= htmlspecialchars($c['nip'] ?? 'N/A') ?></p>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th width="35%"><i class="fas fa-calendar-check text-primary"></i> Jenis Cuti:</th>
                                            <td><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-map-marker-alt text-primary"></i> Tujuan:</th>
                                            <td><?= htmlspecialchars($c['tujuan'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-calendar text-primary"></i> Periode Cuti:</th>
                                            <td>
                                                <?= date('d F Y', strtotime($c['mulai'])) ?> -
                                                <?= date('d F Y', strtotime($c['berakhir'])) ?>
                                                <br><small
                                                    class="text-muted">(<?= ($c['jumlah_hari'] ?? round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1)) ?>
                                                    hari)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-comment text-primary"></i> Alasan:</th>
                                            <td><?= htmlspecialchars($c['alasan'] ?? 'N/A') ?></td>
                                        </tr>
                                        <tr>
                                            <th><i class="fas fa-clipboard-list text-primary"></i> Status:</th>
                                            <td>
                                                <?php
                                                switch ($c['id_status_cuti']) {
                                                    case 1:
                                                        echo '<span class="badge badge-warning badge-lg"><i class="fas fa-clock"></i> Menunggu Konfirmasi</span>';
                                                        break;
                                                    case 2:
                                                        echo '<span class="badge badge-success badge-lg"><i class="fas fa-check"></i> Disetujui</span>';
                                                        break;
                                                    case 3:
                                                        echo '<span class="badge badge-danger badge-lg"><i class="fas fa-times"></i> Ditolak</span>';
                                                        break;
                                                    case 4:
                                                        echo '<span class="badge badge-info badge-lg"><i class="fas fa-eye"></i> Sedang Direview</span>';
                                                        break;
                                                    default:
                                                        echo '<span class="badge badge-secondary">Unknown</span>';
                                                        break;
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php if (!empty($c['alasan_verifikasi'])): ?>
                                            <tr>
                                                <th><i class="fas fa-comment-dots text-primary"></i> Catatan:</th>
                                                <td>
                                                    <div class="alert alert-info mb-0">
                                                        <?= htmlspecialchars($c['alasan_verifikasi']) ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <?php if ($c['id_status_cuti'] == 2): ?>
                                <a href="<?= base_url('Surat/cetak_surat/' . $c['id_cuti']) ?>" class="btn btn-success"
                                    target="_blank">
                                    <i class="fas fa-print"></i> Cetak Surat
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->load->view("pegawai/components/js.php") ?>

    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#cutiTable').DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": true,
                "info": true,
                "paging": true,
                "searching": true,
                "pageLength": 10,
                "language": {
                    "search": "Cari:",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": "Belum ada pengajuan cuti",
                    "zeroRecords": "Tidak ada data yang cocok dengan pencarian"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [7] }
                ]
            });
        });
    </script>
</body>

</html>