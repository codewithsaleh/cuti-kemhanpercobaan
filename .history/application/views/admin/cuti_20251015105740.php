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
                                        <?php $no = 1; foreach($cuti as $c): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td>
                                                <strong><?= htmlspecialchars($c['nama_lengkap']) ?></strong>
                                                <br><small class="text-muted"><?= htmlspecialchars($c['nip'] ?? 'N/A') ?></small>
                                            </td>
                                            <td>
                                                <span class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                            </td>
                                            <td>
                                                <small><?= date('d/m/Y', strtotime($c['mulai'])) ?> - <?= date('d/m/Y', strtotime($c['berakhir'])) ?></small>
                                                <br><small class="text-success"><strong><?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?> hari</strong></small>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                switch($c['id_status_cuti']) {
                                                    case 1: 
                                                        echo '<span class="badge badge-secondary">Menunggu Atasan</span>';
                                                        break;
                                                    case 2: 
                                                        echo '<span class="badge badge-success">Disetujui Final</span>';
                                                        break;
                                                    case 3: 
                                                        echo '<span class="badge badge-danger">Ditolak</span>';
                                                        break;
                                                    case 4: 
                                                        echo '<span class="badge badge-warning">Menunggu Admin</span>';
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
                                                <?php if($c['id_status_cuti'] == 4): ?>
                                                <button class="btn btn-sm btn-success" data-toggle="modal" 
                                                    data-target="#verifikasi_<?= $c['id_cuti'] ?>" title="Verifikasi Admin">
                                                    <i class="fas fa-check"></i>
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
        <?php foreach($cuti as $c): ?>
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
                                            switch($c['id_status_cuti']) {
                                                case 1: echo '<i class="fas fa-clock text-warning"></i> Menunggu'; break;
                                                case 2: echo '<i class="fas fa-check text-success"></i> Disetujui'; break;
                                                case 3: echo '<i class="fas fa-times text-danger"></i> Ditolak'; break;
                                                case 4: echo '<i class="fas fa-eye text-info"></i> Review'; break;
                                                default: echo 'Unknown'; break;
                                            }
                                            ?>
                                        </span>
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
                                                        <th width="40%"><i class="fas fa-calendar-check text-primary"></i> Jenis Cuti:</th>
                                                        <td><span class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-calendar text-primary"></i> Mulai Cuti:</th>
                                                        <td><strong><?= date('d F Y', strtotime($c['mulai'])) ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-calendar text-primary"></i> Berakhir Cuti:</th>
                                                        <td><strong><?= date('d F Y', strtotime($c['berakhir'])) ?></strong></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-clock text-primary"></i> Durasi:</th>
                                                        <td><span class="badge badge-success"><?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?> hari</span></td>
                                                    </tr>
                                                    <tr>
                                                        <th>Tanggal Diajukan:</th>
                                                        <td>
                                                            <?php 
                                                            // Gunakan timestamp yang akurat atau fallback ke tanggal
                                                            $tgl_tampil = !empty($c['tgl_diajukan_timestamp']) ? 
                                                                $c['tgl_diajukan_timestamp'] : 
                                                                (!empty($c['tgl_diajukan_full']) ? $c['tgl_diajukan_full'] : $c['tgl_diajukan'] . ' 00:00:00');
                                                            echo date('d F Y H:i', strtotime($tgl_tampil)) . ' WIB';
                                                            ?>
                                                        </td>
                                                    </tr>
                                                    
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
                                                    
                                                    <!-- Tampilkan catatan umum jika tidak ada catatan khusus -->
                                                    <?php if (!empty($c['alasan_verifikasi']) && empty($c['catatan_atasan']) && empty($c['catatan_admin'])): ?>
                                                    <tr>
                                                        <th>Catatan:</th>
                                                        <td><?= htmlspecialchars($c['alasan_verifikasi']) ?></td>
                                                    </tr>
                                                    <?php endif; ?>

                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless table-sm">
                                                    <tr>
                                                        <th width="40%"><i class="fas fa-map-marker-alt text-primary"></i> Tujuan:</th>
                                                        <td><?= htmlspecialchars($c['tujuan'] ?? 'N/A') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-car text-primary"></i> Berkendaraan:</th>
                                                        <td><?= htmlspecialchars($c['berkendaraan'] ?? 'Tidak disebutkan') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-users text-primary"></i> Pengikut:</th>
                                                        <td><?= htmlspecialchars($c['pengikut'] ?? 'Tidak ada') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th><i class="fas fa-clipboard-list text-primary"></i> Keperluan:</th>
                                                        <td><?= htmlspecialchars($c['keperluan'] ?? 'N/A') ?></td>
                                                    </tr>
                                                    <?php if(!empty($c['alasan_verifikasi'])): ?>
                                                    <tr>
                                                        <th><i class="fas fa-comment text-primary"></i> Catatan Admin:</th>
                                                        <td>
                                                            <div class="alert alert-info alert-sm mb-0">
                                                                <?= htmlspecialchars($c['alasan_verifikasi']) ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <?php if($c['id_status_cuti'] == 4): ?>
                        <!-- Tombol verifikasi hanya muncul untuk status 4 (Menunggu Admin) -->
                        <button type="button" class="btn btn-success" data-dismiss="modal" 
                            data-toggle="modal" data-target="#verifikasi_<?= $c['id_cuti'] ?>">
                            <i class="fas fa-check"></i> Verifikasi Sekarang
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Verifikasi - Hanya untuk status 4 -->
        <?php if($c['id_status_cuti'] == 4): ?>
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
                            <strong>Informasi:</strong> Cuti ini sudah disetujui oleh atasan. Anda sebagai admin memberikan persetujuan final.
                        </div>
                        
                        <form action="<?= base_url('Cuti/confirm_cuti') ?>" method="POST">
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
                                        id="setuju_final_<?= $c['id_cuti'] ?>" value="2" required>
                                    <label class="form-check-label text-success" for="setuju_final_<?= $c['id_cuti'] ?>">
                                        <i class="fas fa-check"></i> Setujui Final (Kurangi Jatah Cuti)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="id_status_cuti" 
                                        id="tolak_final_<?= $c['id_cuti'] ?>" value="3" required>
                                    <label class="form-check-label text-danger" for="tolak_final_<?= $c['id_cuti'] ?>">
                                        <i class="fas fa-times"></i> Tolak Pengajuan Cuti
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Catatan Admin:</label>
                                <textarea class="form-control" name="alasan_verifikasi" rows="3" 
                                    placeholder="Berikan catatan untuk keputusan admin..."></textarea>
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
    $(document).ready(function() {
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
</body>
</html>
