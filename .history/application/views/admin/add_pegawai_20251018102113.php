<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <?php if ($this->session->flashdata('input')): ?>
    <script>
    swal({
        title: "Success!",
        text: "Permohonan Cuti Berhasil Diajukan!",
        icon: "success"
    });
    </script>
    <?php endif; ?>

    <?php if ($this->session->flashdata('eror_input')): ?>
    <script>
    swal({
        title: "Error!",
        text: "<?= $this->session->flashdata('eror_input') ?>",
        icon: "error"
    });
    </script>
    <?php endif; ?>

    <div class="wrapper">
        <!-- Navbar -->
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <!-- /.navbar -->

        <!-- Sidebar -->
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Formulir Permohonan Cuti</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Dashboard/dashboard_pegawai') ?>">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Permohonan Cuti</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-edit mr-2"></i>
                                        Isi Data Pengajuan Cuti
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <!-- Info Pegawai -->
                                    <div class="alert alert-info">
                                        <h6><i class="fas fa-user"></i> Informasi Pegawai:</h6>
                                        <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($user['nama_lengkap'] ?? 'N/A') ?>
                                        </p>
                                        <p class="mb-1"><strong>NIP:</strong> <?= htmlspecialchars($user['nip'] ?? 'N/A') ?></p>
                                        <p class="mb-0"><strong>Sisa Cuti:</strong> <span class="badge badge-success"><?= $user['sisa_cuti'] ?? 12 ?> hari
                                            </span></p>
                                    </div>

                                    <form action="<?= base_url('Form_Cuti/proses_cuti') ?>" method="POST">
                                        <input type="hidden" name="id_user" value="<?= $this->session->userdata('id_user') ?>">

                                        <!-- Jenis Cuti -->
                                        <div class="form-group">
                                            <label for="id_jenis_cuti">Jenis Cuti <span class="text-danger">*</span></label>
                                            <select class="form-control" id="id_jenis_cuti" name="id_jenis_cuti" required>
                                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                                <?php if (!empty($jenis_cuti_data)): ?>
                                                <?php foreach($jenis_cuti_data as $jc): ?>
                                                <option value="<?= $jc["id_jenis_cuti"] ?>">
                                                    <?= htmlspecialchars($jc["nama_cuti"]) ?> 
                                                    <?php if ($jc["max_hari"]): ?>
                                                    (Max: <?= $jc["max_hari"] ?> hari)
                                                    <?php endif; ?>
                                                </option>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <option value="">Tidak ada jenis cuti tersedia</option>
                                                <?php endif; ?>
                                            </select>
                                        </div>

                                        <!-- Perihal Cuti -->
                                        <div class="form-group">
                                            <label for="perihal_cuti">Perihal Cuti <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="perihal_cuti"
                                                placeholder="Contoh: Keperluan Keluarga atau Cuti Tahunan" required>
                                        </div>

                                        <!-- Alasan Lengkap -->
                                        <div class="form-group">
                                            <label for="alasan">Alasan Lengkap <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="alasan" rows="4"
                                                placeholder="Jelaskan alasan Anda mengajukan cuti secara rinci" required></textarea>
                                        </div>

                                        <!-- Tanggal Mulai dan Berakhir -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Mulai Cuti <span class="text-danger">*</span></label>
                                                    <input type="date" name="mulai" class="form-control" 
                                                           min="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Berakhir Cuti <span class="text-danger">*</span></label>
                                                    <input type="date" name="berakhir" class="form-control" 
                                                           min="<?= date('Y-m-d') ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Field Tambahan (Opsional) -->
                                        <div class="card card-secondary collapsed-card">
                                            <div class="card-header">
                                                <h3 class="card-title">Detail Tambahan (Opsional)</h3>
                                                <div class="card-tools">
                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="card-body" style="display: none;">
                                                <div class="form-group">
                                                    <label>Tujuan</label>
                                                    <input type="text" class="form-control" name="tujuan" 
                                                           placeholder="Lokasi/tempat tujuan cuti">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Berkendaraan</label>
                                                    <select class="form-control" name="berkendaraan">
                                                        <option value="">-- Pilih Kendaraan --</option>
                                                        <option value="Mobil Pribadi">Mobil Pribadi</option>
                                                        <option value="Motor Pribadi">Motor Pribadi</option>
                                                        <option value="Mobil Dinas">Mobil Dinas</option>
                                                        <option value="Pesawat">Pesawat</option>
                                                        <option value="Kereta Api">Kereta Api</option>
                                                        <option value="Bus">Bus</option>
                                                        <option value="Tidak Berkendaraan">Tidak Berkendaraan</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Pengikut</label>
                                                    <input type="text" class="form-control" name="pengikut" 
                                                           placeholder="Siapa yang ikut (keluarga, dll)">
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label>Keperluan Detail</label>
                                                    <textarea class="form-control" name="keperluan" rows="3" 
                                                              placeholder="Keperluan detail selama cuti"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Ajukan Permohonan
                                        </button>
                                        <a href="<?= base_url('Dashboard/dashboard_pegawai') ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Batal
                                        </a>
                                        <a href="<?= base_url('Cuti/view_pegawai') ?>" class="btn btn-info">
                                            <i class="fas fa-list"></i> Lihat Data Cuti
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Info Panel -->
                        <div class="col-md-4">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle"></i> Informasi Penting
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Pastikan data yang diisi sudah benar</li>
                                        <li><i class="fas fa-check text-success"></i> Ajukan cuti minimal 3 hari sebelumnya</li>
                                        <li><i class="fas fa-check text-success"></i> Sisa cuti Anda: <strong><?= $user['sisa_cuti'] ?? 12 ?> hari</strong></li>
                                        <li><i class="fas fa-check text-success"></i> Jatah cuti tahunan: <strong><?= $user['jatah_cuti'] ?? 12 ?> hari</strong></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-exclamation-triangle"></i> Catatan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-sm">
                                        Pengajuan cuti akan diproses melalui atasan langsung terlebih dahulu, 
                                        kemudian akan diteruskan ke admin untuk persetujuan final.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer text-center">
            <strong>Copyright &copy; 2024 <a href="#">SI Cuti Kemhan</a>.</strong>
        </footer>
    </div>

    <?php $this->load->view("pegawai/components/js.php") ?>

    <script>
    // Validasi tanggal
    $('input[name="mulai"]').change(function() {
        var mulai = $(this).val();
        $('input[name="berakhir"]').attr('min', mulai);
        
        // Reset tanggal berakhir jika lebih kecil dari mulai
        var berakhir = $('input[name="berakhir"]').val();
        if (berakhir && berakhir < mulai) {
            $('input[name="berakhir"]').val(mulai);
        }
    });

    // Validasi form sebelum submit
    $('form').submit(function(e) {
        var mulai = new Date($('input[name="mulai"]').val());
        var berakhir = new Date($('input[name="berakhir"]').val());
        var sisaCuti = <?= $user['sisa_cuti'] ?? 12 ?>;
        
        if (mulai > berakhir) {
            e.preventDefault();
            swal({
                title: "Error!",
                text: "Tanggal mulai tidak boleh lebih besar dari tanggal berakhir!",
                icon: "error"
            });
            return false;
        }
        
        var selisihHari = Math.ceil((berakhir - mulai) / (1000 * 60 * 60 * 24)) + 1;
        if (selisihHari > sisaCuti) {
            e.preventDefault();
            swal({
                title: "Error!",
                text: "Jumlah hari cuti (" + selisihHari + " hari) melebihi sisa cuti Anda (" + sisaCuti + " hari)!",
                icon: "error"
            });
            return false;
        }
    });
    </script>
</body>
</html>