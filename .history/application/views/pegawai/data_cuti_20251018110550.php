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
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                    <i class="fas fa-calendar-alt mr-2"></i>
                    Riwayat Pengajuan Cuti Saya
                </h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="cutiTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th style="width: 15%;">Jenis Cuti</th>
                                <th style="width: 20%;">Tujuan</th>
                                <th style="width: 18%;">Periode Cuti</th>
                                <th style="width: 12%;">Berkendaraan</th>
                                <th style="width: 12%;">Pengikut</th>
                                <th style="width: 8%;">Status</th>
                                <th style="width: 10%;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($cuti) && is_array($cuti)): ?>
                                <?php $no = 1; foreach($cuti as $c): ?>
                                <tr>
                                    <td class="text-center"><?= $no++ ?></td>
                                    <td>
                                        <span class="badge badge-info"><?= htmlspecialchars($c['nama_cuti'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <?php 
                                        $tujuan = $c['tujuan'] ?? 'N/A';
                                        echo htmlspecialchars(strlen($tujuan) > 25 ? substr($tujuan, 0, 25) . '...' : $tujuan);
                                        ?>
                                    </td>
                                    <td>
                                        <small><i class="fas fa-calendar-check text-primary"></i> <?= date('d/m/Y', strtotime($c['mulai'])) ?></small>
                                        <br><small class="text-muted">s/d <?= date('d/m/Y', strtotime($c['berakhir'])) ?></small>
                                        <br><small class="text-success"><strong><?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?> hari</strong></small>
                                    </td>
                                    <td>
                                        <small><i class="fas fa-car text-info"></i> <?= htmlspecialchars($c['berkendaraan'] ?? 'Tidak ada') ?></small>
                                    </td>
                                    <td>
                                        <small><i class="fas fa-users text-secondary"></i> <?= htmlspecialchars($c['pengikut'] ?? 'Tidak ada') ?></small>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        switch($c['id_status_cuti']) {
                                            case 1: 
                                                echo '<span class="badge badge-warning"><i class="fas fa-clock"></i> Menunggu</span>';
                                                break;
                                            case 2: 
                                                echo '<span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>';
                                                break;
                                            case 3: 
                                                echo '<span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>';
                                                break;
                                            case 4:
                                                echo '<span class="badge badge-info"><i class="fas fa-eye"></i> Review</span>';
                                                break;
                                            default: 
                                                echo '<span class="badge badge-secondary">Unknown</span>';
                                                break;
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info" data-toggle="modal" 
                                                data-target="#detail_<?= $c['id_cuti'] ?>" title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            <!-- Tombol Cetak Laporan dengan ikon printer -->
                                            <a href="<?= base_url('Form_Cuti/cetak_laporan_cuti/' . $c['id_cuti']) ?>" 
                                               class="btn btn-sm btn-success" target="_blank" title="Cetak Surat Permohonan Cuti">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            
                                            <?php if($c['id_status_cuti'] == 1): ?>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal" 
                                                data-target="#edit_<?= $c['id_cuti'] ?>" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" data-toggle="modal" 
                                                data-target="#hapus_<?= $c['id_cuti'] ?>" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-info-circle text-muted fa-2x mb-3"></i>
                                        <br><span class="text-muted">Belum ada pengajuan cuti</span>
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
        </div>
    </div>

    <!-- Modal Detail (di luar loop untuk menghindari konflik) -->
    <?php if (!empty($cuti) && is_array($cuti)): ?>
        <?php foreach($cuti as $c): ?>
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
                                <div class="bg-primary rounded p-3 mb-3 text-black">
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
                                            <br><small class="text-muted">(<?= round((strtotime($c['berakhir']) - strtotime($c['mulai'])) / (60 * 60 * 24) + 1) ?> hari)</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-car text-primary"></i> Berkendaraan:</th>
                                        <td><?= htmlspecialchars($c['berkendaraan'] ?? 'Tidak ada') ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-users text-primary"></i> Pengikut:</th>
                                        <td><?= htmlspecialchars($c['pengikut'] ?? 'Tidak ada') ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fas fa-clipboard-list text-primary"></i> Keperluan:</th>
                                        <td><?= htmlspecialchars($c['keperluan'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal Diajukan:</th>
                                        <td>
                                            <?php 
                                            // Gunakan timestamp yang akurat atau fallback ke tanggal + waktu default
                                            $tgl_tampil = !empty($c['tgl_diajukan_timestamp']) ? 
                                                $c['tgl_diajukan_timestamp'] : 
                                                (!empty($c['tgl_diajukan_full']) ? $c['tgl_diajukan_full'] : $c['tgl_diajukan'] . ' 00:00:00');
                                            echo date('d F Y H:i', strtotime($tgl_tampil)) . ' WIB';
                                            ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Progress Status Persetujuan -->
                                    <tr>
                                        <th>Progress Persetujuan:</th>
                                        <td>
                                            <?php if($c['id_status_cuti'] == 1): ?>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-warning" style="width: 33%">Menunggu Atasan</div>
                                                </div>
                                            <?php elseif($c['id_status_cuti'] == 4): ?>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" style="width: 66%">Menunggu Admin</div>
                                                </div>
                                            <?php elseif($c['id_status_cuti'] == 2): ?>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-success" style="width: 100%">Disetujui Final</div>
                                                </div>
                                            <?php elseif($c['id_status_cuti'] == 3): ?>
                                                <div class="progress mb-2">
                                                    <div class="progress-bar bg-danger" style="width: 100%">Ditolak</div>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    
                                    <!-- Catatan dari Atasan -->
                                    <?php if (!empty($c['catatan_atasan'])): ?>
                                    <tr>
                                        <th>Catatan Atasan:</th>
                                        <td>
                                            <div class="alert alert-info">
                                                <i class="fas fa-user-tie mr-2"></i>
                                                <strong>Atasan:</strong><br>
                                                <?= htmlspecialchars($c['catatan_atasan']) ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <!-- Catatan dari Admin -->
                                    <?php if (!empty($c['catatan_admin'])): ?>
                                    <tr>
                                        <th>Catatan Admin:</th>
                                        <td>
                                            <div class="alert alert-warning">
                                                <i class="fas fa-user-shield mr-2"></i>
                                                <strong>Admin:</strong><br>
                                                <?= htmlspecialchars($c['catatan_admin']) ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endif; ?>
                                    
                                    <!-- Catatan umum jika tidak ada catatan khusus -->
                                    <?php if (!empty($c['alasan_verifikasi']) && empty($c['catatan_atasan']) && empty($c['catatan_admin'])): ?>
                                    <tr>
                                        <th>Catatan:</th>
                                        <td><?= htmlspecialchars($c['alasan_verifikasi']) ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <?php if($c['id_status_cuti'] == 2): ?>
                        <a href="<?= base_url('Form_Cuti/cetak_surat/' . $c['id_cuti']) ?>" 
                           class="btn btn-success" target="_blank">
                            <i class="fas fa-print"></i> Cetak Surat
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit (hanya untuk status menunggu) -->
        <?php if($c['id_status_cuti'] == 1): ?>
        <div class="modal fade" id="edit_<?= $c['id_cuti'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Pengajuan Cuti</h5>
                        <button type="button" class="close text-white" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('Form_Cuti/edit_cuti') ?>" method="POST">
                            <input type="hidden" name="id_cuti" value="<?= $c['id_cuti'] ?>">
                            <input type="hidden" name="tgl_diajukan" value="<?= $c['tgl_diajukan'] ?>">
                            
                            <div class="form-group">
                                <label>Tujuan</label>
                                <textarea class="form-control" name="tujuan" rows="3" required><?= htmlspecialchars($c['tujuan'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="mulai" value="<?= $c['mulai'] ?>" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" class="form-control" name="berakhir" value="<?= $c['berakhir'] ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Berkendaraan</label>
                                <select class="form-control" name="berkendaraan">
                                    <option value="">-- Pilih Jenis Kendaraan --</option>
                                    <option value="Mobil Pribadi" <?= ($c['berkendaraan'] ?? '') == 'Mobil Pribadi' ? 'selected' : '' ?>>Mobil Pribadi</option>
                                    <option value="Motor Pribadi" <?= ($c['berkendaraan'] ?? '') == 'Motor Pribadi' ? 'selected' : '' ?>>Motor Pribadi</option>
                                    <option value="Mobil Dinas" <?= ($c['berkendaraan'] ?? '') == 'Mobil Dinas' ? 'selected' : '' ?>>Mobil Dinas</option>
                                    <option value="Pesawat" <?= ($c['berkendaraan'] ?? '') == 'Pesawat' ? 'selected' : '' ?>>Pesawat</option>
                                    <option value="Kereta Api" <?= ($c['berkendaraan'] ?? '') == 'Kereta Api' ? 'selected' : '' ?>>Kereta Api</option>
                                    <option value="Bus" <?= ($c['berkendaraan'] ?? '') == 'Bus' ? 'selected' : '' ?>>Bus</option>
                                    <option value="Kapal Laut" <?= ($c['berkendaraan'] ?? '') == 'Kapal Laut' ? 'selected' : '' ?>>Kapal Laut</option>
                                    <option value="Tidak Berkendaraan" <?= ($c['berkendaraan'] ?? '') == 'Tidak Berkendaraan' ? 'selected' : '' ?>>Tidak Berkendaraan</option>
                                    <option value="Lainnya" <?= ($c['berkendaraan'] ?? '') == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label>Pengikut</label>
                                <input type="text" class="form-control" name="pengikut" value="<?= htmlspecialchars($c['pengikut'] ?? '') ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Keperluan</label>
                                <textarea class="form-control" name="keperluan" rows="3" required><?= htmlspecialchars($c['keperluan'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php $this->load->view("pegawai/components/js.php") ?>
    
    <script>
    $(document).ready(function() {
        // Hapus DataTable yang ada jika sudah ada
        if ($.fn.DataTable.isDataTable('#cutiTable')) {
            $('#cutiTable').DataTable().destroy();
        }
        
        // Inisialisasi DataTable baru dengan ID yang unik
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
                { "orderable": false, "targets": [7] } // Kolom aksi tidak bisa diurutkan
            ]
        });
    });
    </script>
</body>
</html>
