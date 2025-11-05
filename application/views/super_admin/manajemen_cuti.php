<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view('super_admin/components/header'); ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view('super_admin/components/navbar'); ?>
        <?php $this->load->view('super_admin/components/sidebar'); ?>
        <style>
            #example1 thead th {
                background-color: #4e73df;
                color: white;
            }
        </style>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Manajemen Cuti Tim</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Atasan/dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Manajemen Cuti</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Pengajuan Cuti dari Pegawai</h3>
                                </div>
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Pegawai</th>
                                                <th>NIP</th>
                                                <th>Tanggal Pengajuan</th>
                                                <th>Jenis Cuti</th>
                                                <th>Durasi</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- [PERBAIKAN] Mengubah nama variabel dari $cuti_bawahan menjadi $cuti_list -->
                                            <?php $no = 1;
                                            foreach ($cuti_list as $cuti): ?>
                                                <tr>
                                                    <td><?= $no++; ?></td>
                                                    <td><?= $cuti['nama_lengkap'] ?></td>
                                                    <td><?= $cuti['nip'] ?></td>
                                                    <td><?= date('d M Y', strtotime($cuti['tgl_diajukan'])) ?></td>
                                                    <td><?= $cuti['nama_cuti'] ?? 'Cuti Tahunan' ?></td>
                                                    <td><?= date('d M Y', strtotime($cuti['mulai'])) ?> s/d
                                                        <?= date('d M Y', strtotime($cuti['berakhir'])) ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($cuti['id_status_cuti'] == 1) { ?>
                                                            <span class="badge badge-warning">Menunggu Persetujuan Anda</span>
                                                        <?php } else if ($cuti['id_status_cuti'] == 2) { ?>
                                                                <span class="badge badge-success">Disetujui Final</span>
                                                        <?php } else if ($cuti['id_status_cuti'] == 3) { ?>
                                                                    <span class="badge badge-danger">Ditolak</span>
                                                        <?php } else if ($cuti['id_status_cuti'] == 4) { ?>
                                                                        <span class="badge badge-info">Menunggu Verifikasi Admin</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td class="text-center">
    <?php if ($cuti['id_status_cuti'] == 1): ?>
            <div class="btn-group btn-group-sm" role="group">
                <!-- Tombol Setujui dengan Modal -->
                <button type="button" class="btn btn-success"
                    data-toggle="modal"
                    data-target="#setujuiModal<?= $cuti['id_cuti'] ?>"
                    title="Setujui Cuti">
                    <i class="fas fa-check-circle"></i>
                </button>

                <!-- Tombol Tolak dengan Modal -->
                <button type="button" class="btn btn-danger" data-toggle="modal"
                    data-target="#tolakModal<?= $cuti['id_cuti'] ?>"
                    title="Tolak Cuti">
                    <i class="fas fa-times-circle"></i>
                </button>
            </div>

            <!-- Modal Setujui (TIDAK BERUBAH) -->
            <div class="modal fade" id="setujuiModal<?= $cuti['id_cuti'] ?>" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-success">
                                <i class="fas fa-check-circle mr-2"></i>Setujui Pengajuan Cuti
                            </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle mr-2"></i>
                                <strong>Perhatian:</strong> Cuti yang disetujui akan diteruskan ke Admin untuk verifikasi final.
                            </div>
            
                            <p>Apakah Anda yakin ingin <strong>menyetujui</strong> pengajuan cuti ini?</p>
            
                            <div class="alert alert-info">
                                <strong>Detail Pengajuan:</strong><br>
                                <strong>Pegawai:</strong> <?= htmlspecialchars($cuti['nama_lengkap'] ?? 'N/A') ?><br>
                                <strong>NIP:</strong> <?= htmlspecialchars($cuti['nip'] ?? 'N/A') ?><br>
                                <strong>Jenis Cuti:</strong> <?= htmlspecialchars($cuti['nama_cuti'] ?? 'N/A') ?><br>
                                <strong>Periode:</strong> <?= date('d/m/Y', strtotime($cuti['mulai'])) ?> -
                                <?= date('d/m/Y', strtotime($cuti['berakhir'])) ?><br>
                                <strong>Lama Cuti:</strong>
                                <?php
                                $mulai = new DateTime($cuti['mulai']);
                                $berakhir = new DateTime($cuti['berakhir']);
                                $jumlah_hari = $berakhir->diff($mulai)->days + 1;
                                echo $jumlah_hari . ' hari';
                                ?>
                            </div>
            
                            <div class="alert alert-light">
                                <strong>Alasan Cuti:</strong><br>
                                <?= nl2br(htmlspecialchars($cuti['alasan'] ?? 'Tidak ada alasan')) ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                            <a href="<?= base_url(); ?>Atasan/setujui_cuti/<?= $cuti['id_cuti'] ?>" class="btn btn-success">
                                <i class="fas fa-check-circle mr-1"></i> Ya, Setujui & Kirim ke Admin
                            </a>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal Tolak (TIDAK BERUBAH) -->
            <div class="modal fade" id="tolakModal<?= $cuti['id_cuti'] ?>" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="fas fa-times-circle mr-2"></i>Tolak Pengajuan Cuti
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="<?= base_url(); ?>Atasan/tolak_cuti/<?= $cuti['id_cuti'] ?>" method="POST">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Perhatian:</strong> Keputusan penolakan ini akan dicatat dan dikirimkan ke pegawai.
                    </div>
                    
                    <p>Apakah Anda yakin ingin <strong>menolak</strong> pengajuan cuti ini?</p>
                    
                    <div class="alert alert-warning">
                        <strong>Detail Pengajuan:</strong><br>
                        <strong>Pegawai:</strong> <?= htmlspecialchars($cuti['nama_lengkap'] ?? 'N/A') ?><br>
                        <strong>NIP:</strong> <?= htmlspecialchars($cuti['nip'] ?? 'N/A') ?><br>
                        <strong>Jenis Cuti:</strong> <?= htmlspecialchars($cuti['nama_cuti'] ?? 'N/A') ?><br>
                        <strong>Periode:</strong> <?= date('d/m/Y', strtotime($cuti['mulai'])) ?> - <?= date('d/m/Y', strtotime($cuti['berakhir'])) ?><br>
                        <strong>Lama Cuti:</strong> 
                        <?php
                        $mulai = new DateTime($cuti['mulai']);
                        $berakhir = new DateTime($cuti['berakhir']);
                        $jumlah_hari = $berakhir->diff($mulai)->days + 1;
                        echo $jumlah_hari . ' hari';
                        ?>
                    </div>
                    
                    <div class="alert alert-light">
                        <strong>Alasan Cuti:</strong><br>
                        <?= nl2br(htmlspecialchars($cuti['alasan'] ?? 'Tidak ada alasan')) ?>
                    </div>

                    <!-- Input Alasan Penolakan -->
                    <div class="form-group">
                        <label for="alasan_penolakan_<?= $cuti['id_cuti'] ?>">
                            <strong>Alasan Penolakan:</strong>
                        </label>
                        <textarea class="form-control" 
                            id="alasan_penolakan_<?= $cuti['id_cuti'] ?>"
                            name="alasan_penolakan" rows="3"
                            placeholder="Masukkan alasan penolakan yang jelas dan informatif..."
                            required></textarea>
                        <small class="text-muted">Wajib diisi untuk memberikan informasi ke pegawai</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle mr-1"></i> Ya, Tolak Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php else: ?>
            <!-- Status Badge untuk cuti yang sudah diproses (TIDAK BERUBAH) -->
            <?php
            $status_badge = '';
            $status_text = '';
            $status_icon = '';

            switch ($cuti['id_status_cuti']) {
                case 2:
                    $status_badge = 'success';
                    $status_text = 'Disetujui';
                    $status_icon = 'fa-check';
                    break;
                case 3:
                    $status_badge = 'danger';
                    $status_text = 'Ditolak';
                    $status_icon = 'fa-times';
                    break;
                case 4:
                    $status_badge = 'info';
                    $status_text = 'Menunggu Admin';
                    $status_icon = 'fa-user-shield';
                    break;
                default:
                    $status_badge = 'secondary';
                    $status_text = 'Diproses';
                    $status_icon = 'fa-clock';
                    break;
            }
            echo '<span class="badge badge-' . $status_badge . ' p-2"><i class="fas ' . $status_icon . ' mr-1"></i>' . $status_text . '</span>';
            ?>

            <?php if (!empty($cuti['approved_at'])): ?>
                    <br><small
                        class="text-muted"><?= date('d/m/Y', strtotime($cuti['approved_at'])) ?></small>
            <?php endif; ?>
    <?php endif; ?>
</td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">SI Cuti Kemhan</a>.</strong>
        </footer>
    </div>

    <?php $this->load->view('super_admin/components/js'); ?>
</body>

</html>