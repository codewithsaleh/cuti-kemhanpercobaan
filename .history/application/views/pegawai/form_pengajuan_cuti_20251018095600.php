<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
    <style>
        .info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('input')): ?>
    <script>
    swal({
        title: "Success!",
        text: "<?= $this->session->flashdata('input') ?>",
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
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <div class="content-wrapper">
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

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Info Panel -->
                        <div class="col-md-4">
                            <div class="info-box">
                                <h5><i class="fas fa-user"></i> Informasi Pegawai</h5>
                                <hr style="border-color: rgba(255,255,255,0.3);">
                                <p><strong>Nama:</strong> <?= htmlspecialchars($user['nama_lengkap'] ?? 'N/A') ?></p>
                                <p><strong>NIP:</strong> <?= htmlspecialchars($user['nip'] ?? 'N/A') ?></p>
                                <p><strong>Jabatan:</strong> <?= htmlspecialchars($user['jabatan'] ?? 'N/A') ?></p>
                                <p><strong>Sisa Cuti:</strong> <span class="badge badge-light"><?= $user['sisa_cuti'] ?? 12 ?>
                                    hari</span></p>
                                <p><strong>Jatah Cuti:</strong> <span class="badge badge-light"><?= $user['jatah_cuti'] ?? 12 ?>
                                    hari</span></p>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5><i class="fas fa-info-circle"></i> Ketentuan Cuti</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-check text-success"></i> Ajukan minimal 3 hari sebelumnya</li>
                                        <li><i class="fas fa-check text-success"></i> Maksimal cuti berturut 7 hari</li>
                                        <li><i class="fas fa-check text-success"></i> Pastikan sisa cuti mencukupi</li>
                                        <li><i class="fas fa-check text-success"></i> Isi alasan dengan jelas</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Form Panel -->
                        <div class="col-md-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-edit"></i> Form Pengajuan Cuti</h3>
                                </div>

                                <div class="card-body">
                                    <form action="<?= base_url('Form_Cuti/proses_cuti') ?>" method="POST" id="formCuti">
                                        <input type="hidden" name="id_user" value="<?= $this->session->userdata('id_user') ?>">

                                        <!-- Jenis Cuti -->
                                        <div class="form-group">
                                            <label for="id_jenis_cuti"><i class="fas fa-list text-primary"></i> Jenis Cuti <span
                                                class="text-danger">*</span></label>
                                            <select class="form-control" id="id_jenis_cuti" name="id_jenis_cuti" required>
                                                <option value="" disabled selected>-- Pilih Jenis Cuti --</option>
                                                <?php if (!empty($jenis_cuti_data)): ?>
                                                <?php foreach($jenis_cuti_data as $jc): ?>
                                                <option value="<?= $jc['id_jenis_cuti'] ?>" 
                                                        data-keterangan="<?= htmlspecialchars($jc['keterangan']) ?>"
                                                        data-max="<?= $jc['max_hari'] ?>">
                                                    <?= htmlspecialchars($jc['nama_cuti']) ?>
                                                    <?php if ($jc['max_hari']): ?>
                                                        (Max: <?= $jc['max_hari'] ?> hari)
                                                    <?php endif; ?>
                                                </option>
                                                <?php endforeach; ?>
                                                <?php else: ?>
                                                <option value="" disabled>Tidak ada jenis cuti tersedia</option>
                                                <?php endif; ?>
                                            </select>
                                            <small id="keteranganCuti" class="form-text text-muted"></small>
                                        </div>

                                        <!-- Perihal Cuti -->
                                        <div class="form-group">
                                            <label for="perihal_cuti"><i class="fas fa-tag text-primary"></i> Perihal Cuti <span
                                                class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="perihal_cuti" id="perihal_cuti"
                                                placeholder="Contoh: Keperluan Keluarga, Liburan Tahunan, dll" required>
                                        </div>

                                        <!-- Alasan Lengkap -->
                                        <div class="form-group">
                                            <label for="alasan"><i class="fas fa-comment text-primary"></i> Alasan Lengkap <span
                                                class="text-danger">*</span></label>
                                            <textarea class="form-control" name="alasan" id="alasan" rows="4"
                                                placeholder="Jelaskan alasan Anda mengajukan cuti secara rinci dan jelas" required></textarea>
                                        </div>

                                        <!-- Tanggal Mulai dan Berakhir -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="mulai"><i class="fas fa-calendar-alt text-primary"></i> Mulai Cuti <span
                                                        class="text-danger">*</span></label>
                                                    <input type="date" name="mulai" id="mulai" class="form-control" 
                                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="berakhir"><i class="fas fa-calendar-alt text-primary"></i> Berakhir Cuti <span
                                                        class="text-danger">*</span></label>
                                                    <input type="date" name="berakhir" id="berakhir" class="form-control" 
                                                           min="<?= date('Y-m-d', strtotime('+1 day')) ?>" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Info Durasi -->
                                        <div class="alert alert-info" id="infoDurasi" style="display: none;">
                                            <i class="fas fa-info-circle"></i>
                                            <span id="textDurasi"></span>
                                        </div>

                                        <hr>

                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-paper-plane"></i> Ajukan Permohonan Cuti
                                            </button>
                                            <a href="<?= base_url('Dashboard/dashboard_pegawai') ?>" class="btn btn-secondary btn-lg">
                                                <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <footer class="main-footer text-center">
            <strong>Copyright &copy; 2024 <a href="#">SI Cuti Kemhan</a>.</strong>
        </footer>
    </div>

    <?php $this->load->view("pegawai/components/js.php") ?>

    <script>
    $(document).ready(function() {
        // Tampilkan keterangan jenis cuti
        $('#id_jenis_cuti').change(function() {
            var keterangan = $(this).find(':selected').data('keterangan');
            var maxHari = $(this).find(':selected').data('max');
            
            if (keterangan) {
                $('#keteranganCuti').text(keterangan);
            }
            
            if (maxHari) {
                $('#keteranganCuti').append(' (Maksimal: ' + maxHari + ' hari)');
            }
        });

        // Hitung durasi cuti
        function hitungDurasi() {
            var mulai = $('#mulai').val();
            var berakhir = $('#berakhir').val();
            
            if (mulai && berakhir) {
                var tanggalMulai = new Date(mulai);
                var tanggalBerakhir = new Date(berakhir);
                var timeDiff = tanggalBerakhir.getTime() - tanggalMulai.getTime();
                var dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1;
                
                if (dayDiff > 0) {
                    $('#textDurasi').text('Durasi cuti: ' + dayDiff + ' hari');
                    $('#infoDurasi').show();
                    
                    // Cek jika melebihi sisa cuti
                    var sisaCuti = <?= $user['sisa_cuti'] ?? 12 ?>;
                    if (dayDiff > sisaCuti) {
                        $('#infoDurasi').removeClass('alert-info').addClass('alert-warning');
                        $('#textDurasi').text('Durasi cuti: ' + dayDiff + ' hari (Melebihi sisa cuti: ' + sisaCuti + ' hari)');
                    } else {
                        $('#infoDurasi').removeClass('alert-warning').addClass('alert-info');
                    }
                } else {
                    $('#infoDurasi').hide();
                }
            } else {
                $('#infoDurasi').hide();
            }
        }

        // Event handler untuk perubahan tanggal
        $('#mulai, #berakhir').change(hitungDurasi);

        // Set tanggal berakhir minimal sama dengan tanggal mulai
        $('#mulai').change(function() {
            $('#berakhir').attr('min', $(this).val());
            hitungDurasi();
        });

        // Validasi form sebelum submit
        $('#formCuti').submit(function(e) {
            var mulai = new Date($('#mulai').val());
            var berakhir = new Date($('#berakhir').val());
            var sisaCuti = <?= $user['sisa_cuti'] ?? 12 ?>;
            
            if (berakhir < mulai) {
                e.preventDefault();
                swal({
                    title: "Error!",
                    text: "Tanggal berakhir tidak boleh lebih kecil dari tanggal mulai!",
                    icon: "error"
                });
                return false;
            }
            
            var durasi = Math.ceil((berakhir.getTime() - mulai.getTime()) / (1000 * 3600 * 24)) + 1;
            if (durasi > sisaCuti) {
                e.preventDefault();
                swal({
                    title: "Peringatan!",
                    text: "Durasi cuti (" + durasi + " hari) melebihi sisa cuti Anda (" + sisaCuti + " hari)!",
                    icon: "warning"
                });
                return false;
            }
        });
    });
    </script>
</body>
</html>
