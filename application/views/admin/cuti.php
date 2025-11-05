<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("admin/components/header.php") ?>
    <style>
        #example1 thead th {
            background-color: #4e73df;
            color: white;
            font-weight: 600;
        }

        .modal-header {
            background-color: #4e73df;
            color: white;
        }

        .modal-header .close {
            color: white;
            opacity: 1;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Notifications -->
    <?php if ($this->session->flashdata('input')): ?>
        <script>
            swal({
                title: "Success!",
                text: "Data berhasil diproses!",
                icon: "success"
            });
        </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('eror')): ?>
        <script>
            swal({
                title: "Error!",
                text: "Terjadi kesalahan!",
                icon: "error"
            });
        </script>
    <?php endif; ?>

    <div class="wrapper">
        <?php $this->load->view("admin/components/navbar.php") ?>
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Cuti</h1>
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
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Pengajuan Cuti Pegawai</h3>
                        </div>
                        <div class="card-body">
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Pegawai</th>
                                        <th>Jenis Cuti</th>
                                        <th>Periode Cuti</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cuti) && is_array($cuti)): ?>
                                        <?php $no = 1;
                                        foreach ($cuti as $c): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($c['nama_lengkap']) ?></strong>
                                                    <br><small
                                                        class="text-muted"><?= htmlspecialchars($c['nip'] ?? 'N/A') ?></small>
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                                </td>
                                                <td>
                                                    <small><?= date('d/m/Y', strtotime($c['mulai'])) ?> -
                                                        <?= date('d/m/Y', strtotime($c['berakhir'])) ?></small>
                                                    <br><small
                                                        class="text-success"><strong><?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?>
                                                            hari</strong></small>
                                                </td>
                                                <td class="text-center">
    <?php
        switch ($c['id_status_cuti']) {
            case 1:
                echo '<span class="badge badge-warning">Menunggu Persetujuan Atasan</span>';
                break;
            case 2:
                echo '<span class="badge badge-success">Disetujui Final</span>';
                break;
            case 3:
                // Cek siapa yang menolak
                if (!empty($c['rejected_by'])) {
                    $this->db->select('id_user_level');
                    $this->db->from('user');
                    $this->db->where('id_user', $c['rejected_by']);
                    $rejecter = $this->db->get()->row();

                    if ($rejecter && $rejecter->id_user_level == 3) {
                        echo '<span class="badge badge-danger">Ditolak Atasan</span>';
                    } else {
                        echo '<span class="badge badge-danger">Ditolak Admin</span>';
                    }
                } else {
                    echo '<span class="badge badge-danger">Ditolak</span>';
                }
                break;
            case 4:
                echo '<span class="badge badge-primary">Menunggu Verifikasi Admin</span>';
                break;
            case 5:
                // TAMPILAN BARU UNTUK ARSIP
                $tahun_reset = $c['tahun_reset'] ?? date('Y');
                $status_terakhir = $c['status_terakhir'] ?? 0;

                echo '<span class="badge badge-secondary">Arsip Tahun ' . $tahun_reset . '</span>';
                echo '<br><small class="text-muted">Status Terakhir: ';

                switch ($status_terakhir) {
                    case 1:
                        echo '<span class="text-warning">Menunggu</span>';
                        break;
                    case 2:
                        echo '<span class="text-success">Diterima</span>';
                        break;
                    case 3:
                        echo '<span class="text-danger">Ditolak</span>';
                        break;
                    case 4:
                        echo '<span class="text-primary">Menunggu Admin</span>';
                        break;
                    default:
                        echo '<span class="text-muted">Tidak diketahui</span>';
                        break;
                }
                echo '</small>';
                break;
            default:
                echo '<span class="badge badge-secondary">Unknown</span>';
                break;
        }
        ?>
</td>
                                                <td>
                                                    <!-- Tombol Detail selalu ada -->
                                                    <button class="btn btn-sm btn-info" data-toggle="modal"
                                                        data-target="#detail_<?= $c['id_cuti'] ?>" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>

                                                    <!-- Tombol Verifikasi hanya untuk status 4 (Menunggu Admin) -->
                                                    <?php if ($c['id_status_cuti'] == 4): ?>
                                                        <button class="btn btn-sm btn-success" data-toggle="modal"
                                                            data-target="#verifikasi_<?= $c['id_cuti'] ?>" title="Verifikasi Admin">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    <?php endif; ?>

                                                    <!-- Tombol untuk status 1 (Menunggu Atasan) - Info saja -->
                                                    <?php if ($c['id_status_cuti'] == 1): ?>
                                                        <button class="btn btn-sm btn-secondary" title="Menunggu Persetujuan Atasan"
                                                            disabled>
                                                            <i class="fas fa-clock"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <i class="fas fa-info-circle text-muted fa-2x mb-3"></i>
                                                <br><span class="text-muted">Tidak ada data pengajuan cuti</span>
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

    <!-- Modal Detail Lengkap (di luar loop untuk menghindari konflik) -->
    <?php if (!empty($cuti) && is_array($cuti)): ?>
        <?php foreach ($cuti as $c): ?>
                <div class="modal fade" id="detail_<?= $c['id_cuti'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-info-circle"></i> Detail Lengkap Pengajuan Cuti
                                </h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <!-- Profil Pegawai -->
                                    <div class="col-md-4">
                                        <div class="card bg-primary text-white">
                                            <div class="card-body text-center">
                                                <i class="fas fa-user fa-3x mb-3"></i>
                                                <h5 class="card-title"><?= htmlspecialchars($c['nama_lengkap']) ?></h5>
                                                <p class="card-text">NIP: <?= htmlspecialchars($c['nip'] ?? 'N/A') ?></p>
                                                <span class="badge badge-light badge-lg">
                                                    <?php
                                                    switch ($c['id_status_cuti']) {
                                                        case 1:
                                                            echo '<i class="fas fa-clock text-warning"></i> Menunggu';
                                                            break;
                                                        case 2:
                                                            echo '<i class="fas fa-check text-success"></i> Disetujui';
                                                            break;
                                                        case 3:
                                                            echo '<i class="fas fa-times text-danger"></i> Ditolak';
                                                            break;
                                                        case 4:
                                                            echo '<i class="fas fa-eye text-info"></i> Review';
                                                            break;
                                                        case 5:
                                                            // TAMPILAN ARSIP
                                                            $tahun_reset = $c['tahun_reset'] ?? date('Y');
                                                            echo '<i class="fas fa-archive text-secondary"></i> Arsip ' . $tahun_reset;
                                                            break;
                                                        default:
                                                            echo 'Unknown';
                                                            break;
                                                    }
                                                    ?>
                                                </span>
                                        
                                                <!-- Tampilkan Status Terakhir untuk Arsip -->
                                                <?php if ($c['id_status_cuti'] == 5 && !empty($c['status_terakhir'])): ?>
                                                        <br>
                                                        <small class="text-secondary mt-2">
                                                            Status Terakhir: 
                                                            <?php
                                                            switch ($c['status_terakhir']) {
                                                                case 1:
                                                                    echo '<span class="text-warning">Menunggu</span>';
                                                                    break;
                                                                case 2:
                                                                    echo '<span class="text-success">Diterima</span>';
                                                                    break;
                                                                case 3:
                                                                    echo '<span class="text-danger">Ditolak</span>';
                                                                    break;
                                                                case 4:
                                                                    echo '<span class="text-info">Review</span>';
                                                                    break;
                                                                default:
                                                                    echo '<span class="text-light">Tidak diketahui</span>';
                                                                    break;
                                                            }
                                                            ?>
                                                        </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Detail Cuti -->
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0"><i class="fas fa-clipboard-list"></i> Informasi Pengajuan Cuti</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm">
                                                            <tr>
                                                                <th width="40%"><i class="fas fa-calendar-check text-primary"></i>
                                                                    Jenis Cuti:</th>
                                                                <td><span
                                                                        class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-calendar text-primary"></i> Mulai Cuti:</th>
                                                                <td><strong><?= date('d F Y', strtotime($c['mulai'])) ?></strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-calendar text-primary"></i> Berakhir Cuti:</th>
                                                                <td><strong><?= date('d F Y', strtotime($c['berakhir'])) ?></strong>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-clock text-primary"></i> Durasi:</th>
                                                                <td><span
                                                                        class="badge badge-success"><?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?>
                                                                        hari</span></td>
                                                            </tr>
                                                            <tr>
                                                                <th>Tanggal Diajukan:</th>
                                                                <td>
                                                                    <?php
                                                                    // Langsung pakai created_at yang sudah ada timestamp
                                                                    echo date('d F Y H:i', strtotime($c['created_at'])) . ' WIB';
                                                                    ?>
                                                                </td>
                                                            </tr>

                                                            <!-- Info Arsip -->
                                                            <?php if ($c['id_status_cuti'] == 5 && !empty($c['tahun_reset'])): ?>
                                                                    <tr>
                                                                        <th><i class="fas fa-archive text-secondary"></i> Tahun Arsip:</th>
                                                                        <td>
                                                                            <span class="badge badge-secondary"><?= $c['tahun_reset'] ?></span>
                                                                        </td>
                                                                    </tr>
                                                            <?php endif; ?>

                                                            <!-- Tampilkan catatan atasan jika ada -->
                                                            <?php if (!empty($c['catatan_atasan'])): ?>
                                                                    <tr>
                                                                        <th>Catatan Atasan:</th>
                                                                        <td>
                                                                            <div class="alert alert-info mt-2 mb-0">
                                                                                <i class="fas fa-user-tie"></i>
                                                                                <?= htmlspecialchars($c['catatan_atasan']) ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                            <?php endif; ?>

                                                            <!-- Tampilkan catatan admin jika ada -->
                                                            <?php if (!empty($c['catatan_admin'])): ?>
                                                                    <tr>
                                                                        <th>Catatan Admin:</th>
                                                                        <td>
                                                                            <div class="alert alert-warning mt-2 mb-0">
                                                                                <i class="fas fa-user-shield"></i>
                                                                                <?= htmlspecialchars($c['catatan_admin']) ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <table class="table table-borderless table-sm">
                                                            <tr>
                                                                <th width="40%"><i class="fas fa-map-marker-alt text-primary"></i>
                                                                    Tujuan:</th>
                                                                <td><?= htmlspecialchars($c['tujuan'] ?? 'N/A') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-car text-primary"></i> Berkendaraan:</th>
                                                                <td><?= htmlspecialchars($c['berkendaraan'] ?? 'Tidak disebutkan') ?>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-users text-primary"></i> Pengikut:</th>
                                                                <td><?= htmlspecialchars($c['pengikut'] ?? 'Tidak ada') ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><i class="fas fa-clipboard-list text-primary"></i> Keperluan:
                                                                </th>
                                                                <td><?= htmlspecialchars($c['keperluan'] ?? 'N/A') ?></td>
                                                            </tr>
                                                    
                                                            <!-- Catatan untuk cuti yang disetujui -->
                                                            <?php if (!empty($c['alasan_verifikasi']) && $c['id_status_cuti'] == 2): ?>
                                                                    <tr>
                                                                        <th><i class="fas fa-check text-success"></i> Catatan Persetujuan:</th>
                                                                        <td>
                                                                            <div class="alert alert-success alert-sm mb-0">
                                                                                <?= htmlspecialchars($c['alasan_verifikasi']) ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                            <?php endif; ?>

                                                            <!-- Alasan untuk cuti yang ditolak -->
                                                            <?php if (!empty($c['alasan_penolakan']) && $c['id_status_cuti'] == 3): ?>
                                                                    <tr>
                                                                        <th><i class="fas fa-times text-danger"></i> Alasan Penolakan:</th>
                                                                        <td>
                                                                            <div class="alert alert-danger alert-sm mb-0">
                                                                                <?= htmlspecialchars($c['alasan_penolakan']) ?>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                            <?php endif; ?>

                                                            <!-- Catatan umum jika tidak ada catatan khusus -->
                                                            <?php if (!empty($c['alasan_verifikasi']) && empty($c['catatan_atasan']) && empty($c['catatan_admin']) && $c['id_status_cuti'] != 2 && $c['id_status_cuti'] != 3): ?>
                                                                    <tr>
                                                                        <th><i class="fas fa-comment text-primary"></i> Catatan:</th>
                                                                        <td><?= htmlspecialchars($c['alasan_verifikasi']) ?></td>
                                                                    </tr>
                                                            <?php endif; ?>
                                                        </table>
                                                    </div>
                                                </div>

                                                <!-- Alasan Cuti -->
                                                <div class="row mt-3">
                                                    <div class="col-12">
                                                        <div class="card border-primary">
                                                            <div class="card-header bg-primary text-white">
                                                                <h6 class="mb-0"><i class="fas fa-comment-dots"></i> Alasan Cuti</h6>
                                                            </div>
                                                            <div class="card-body">
                                                                <?= nl2br(htmlspecialchars($c['alasan'] ?? 'Tidak ada alasan')) ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Info Approval Timeline -->
                                                <?php if (!empty($c['approved_at']) || !empty($c['approved_at_atasan'])): ?>
                                                        <div class="row mt-3">
                                                            <div class="col-12">
                                                                <div class="card border-info">
                                                                    <div class="card-header bg-info text-white">
                                                                        <h6 class="mb-0"><i class="fas fa-history"></i> Timeline Persetujuan</h6>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <?php if (!empty($c['approved_at_atasan'])): ?>
                                                                                <p class="mb-1">
                                                                                    <i class="fas fa-user-tie text-success"></i>
                                                                                    <strong>Diverifikasi Atasan:</strong> 
                                                                                    <?= date('d F Y H:i', strtotime($c['approved_at_atasan'])) ?>
                                                                                </p>
                                                                        <?php endif; ?>
                                                                        <?php if (!empty($c['approved_at'])): ?>
                                                                                <p class="mb-0">
                                                                                    <i class="fas fa-user-shield text-primary"></i>
                                                                                    <strong>Diverifikasi Admin:</strong> 
                                                                                    <?= date('d F Y H:i', strtotime($c['approved_at'])) ?>
                                                                                </p>
                                                                        <?php endif; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <?php if ($c['id_status_cuti'] == 4): ?>
                                        <!-- Tombol verifikasi hanya muncul untuk status 4 (Menunggu Admin) -->
                                        <button type="button" class="btn btn-success" data-dismiss="modal" data-toggle="modal"
                                            data-target="#verifikasi_<?= $c['id_cuti'] ?>">
                                            <i class="fas fa-check"></i> Verifikasi Sekarang
                                        </button>
                                <?php endif; ?>
                        
                                
                        
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Verifikasi - Hanya untuk status 4 -->
                <?php if ($c['id_status_cuti'] == 4): ?>
                    <div class="modal fade" id="verifikasi_<?= $c['id_cuti'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Verifikasi Final Admin</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Informasi:</strong> Cuti ini sudah disetujui oleh atasan. Anda sebagai admin memberikan
                                        persetujuan final.
                                    </div>

                                    <form action="<?= base_url('Cuti/proses_verifikasi') ?>" method="POST">
                                        <input type="hidden" name="id_cuti" value="<?= $c['id_cuti'] ?>">

                                        <div class="text-center mb-3">
                                            <h6><?= htmlspecialchars($c['nama_lengkap']) ?></h6>
                                            <p class="text-muted">
                                                <?= date('d/m/Y', strtotime($c['mulai'])) ?> -
                                                <?= date('d/m/Y', strtotime($c['berakhir'])) ?>
                                            </p>
                                            <p class="text-success">
                                                <i class="fas fa-check-circle"></i> Sudah disetujui atasan
                                            </p>
                                        </div>

                                        <div class="form-group">
                                            <label>Keputusan Final Admin:</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id_status_cuti"
                                                    id="setuju_final_<?= $c['id_cuti'] ?>" value="2" required
                                                    onchange="toggleAlasanForm('<?= $c['id_cuti'] ?>', 'setuju')">
                                                <label class="form-check-label text-success" for="setuju_final_<?= $c['id_cuti'] ?>">
                                                    <i class="fas fa-check"></i> Setujui Final (Kurangi Jatah Cuti)
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="id_status_cuti"
                                                    id="tolak_final_<?= $c['id_cuti'] ?>" value="3" required
                                                    onchange="toggleAlasanForm('<?= $c['id_cuti'] ?>', 'tolak')">
                                                <label class="form-check-label text-danger" for="tolak_final_<?= $c['id_cuti'] ?>">
                                                    <i class="fas fa-times"></i> Tolak Pengajuan Cuti
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Form Catatan untuk Disetujui -->
                                        <div class="form-group alasan-form" id="alasan_setuju_<?= $c['id_cuti'] ?>"
                                            style="display: none;">
                                            <label for="alasan_verifikasi_<?= $c['id_cuti'] ?>">
                                                <i class="fas fa-check text-success mr-1"></i>Catatan Persetujuan:
                                            </label>
                                            <textarea class="form-control" id="alasan_verifikasi_<?= $c['id_cuti'] ?>"
                                                name="alasan_verifikasi" rows="3"
                                                placeholder="Berikan catatan persetujuan untuk pegawai..."></textarea>
                                            <small class="text-muted">Catatan ini akan ditampilkan di sisi pegawai untuk cuti yang
                                                disetujui</small>
                                        </div>

                                        <!-- Form Alasan untuk Ditolak -->
                                        <div class="form-group alasan-form" id="alasan_tolak_<?= $c['id_cuti'] ?>"
                                            style="display: none;">
                                            <label for="alasan_penolakan_<?= $c['id_cuti'] ?>">
                                                <i class="fas fa-times text-danger mr-1"></i>Alasan Penolakan:
                                            </label>
                                            <textarea class="form-control" id="alasan_penolakan_<?= $c['id_cuti'] ?>"
                                                name="alasan_penolakan" rows="3" placeholder="Masukkan alasan penolakan..."
                                                required></textarea>
                                            <small class="text-muted text-danger">Wajib diisi untuk memberikan informasi ke
                                                pegawai</small>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-gavel"></i> Keputusan Final
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>


            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->load->view("admin/components/js.php") ?>

    <script>
        $(document).ready(function () {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            $('#example1').DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                "ordering": true,
                "info": true,
                "paging": true,
                "searching": true,
                "pageLength": 15,
                "language": {
                    "search": "Cari:",
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                    "infoFiltered": "(disaring dari _MAX_ total data)",
                    "paginate": {
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    },
                    "emptyTable": "Tidak ada data pengajuan cuti",
                    "zeroRecords": "Tidak ada data yang cocok dengan pencarian"
                },
                "columnDefs": [
                    { "orderable": false, "targets": [5] }, // Kolom aksi tidak bisa diurutkan
                    { "className": "text-center", "targets": [0, 4, 5] } // Kolom tertentu di tengah
                ]
            });
        });
    </script>

    <!-- JavaScript untuk toggle form -->
    <script>
        function toggleAlasanForm(id_cuti, jenis) {
            // Sembunyikan semua form alasan terlebih dahulu
            document.querySelectorAll('.alasan-form').forEach(function (form) {
                form.style.display = 'none';
            });

            // Reset required attribute
            document.getElementById('alasan_penolakan_' + id_cuti).required = false;

            // Tampilkan form sesuai pilihan
            if (jenis === 'setuju') {
                document.getElementById('alasan_setuju_' + id_cuti).style.display = 'block';
            } else if (jenis === 'tolak') {
                document.getElementById('alasan_tolak_' + id_cuti).style.display = 'block';
                document.getElementById('alasan_penolakan_' + id_cuti).required = true;
            }
        }

        // Reset form ketika modal ditutup
        $('#verifikasi_<?= $c['id_cuti'] ?>').on('hidden.bs.modal', function () {
            document.querySelectorAll('.alasan-form').forEach(function (form) {
                form.style.display = 'none';
            });
            document.getElementById('alasan_penolakan_<?= $c['id_cuti'] ?>').required = false;
        });
    </script>
</body>

</html>