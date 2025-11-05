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
                        <h1 class="m-0">Edit Pengajuan Cuti</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="<?= base_url('Dashboard/dashboard_pegawai')?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?= base_url('Cuti/view_pegawai_menunggu')?>">Cuti Menunggu</a></li>
                            <li class="breadcrumb-item active">Edit Cuti</li>
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
                            <i class="fas fa-edit mr-2"></i>
                            Form Edit Pengajuan Cuti
                        </h3>
                        <div class="card-tools">
                            <a href="<?= base_url('Cuti/view_pegawai_menunggu') ?>" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="<?= base_url('Cuti/edit_cuti_pegawai/' . $cuti['id_cuti']) ?>" method="POST">
                            <!-- Jenis Cuti -->
                            <div class="form-group">
                                <label for="id_jenis_cuti">Jenis Cuti <span class="text-danger">*</span></label>
                                <select class="form-control" id="id_jenis_cuti" name="id_jenis_cuti" required>
                                    <option value="" disabled>Pilih Jenis Cuti</option>
                                    <?php foreach($jenis_cuti as $jc): ?>
                                        <option value="<?= $jc['id_jenis_cuti'] ?>" 
                                            <?= ($jc['id_jenis_cuti'] == $cuti['id_jenis_cuti']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($jc['nama_cuti']) ?>
                                        </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <!-- Perihal Cuti -->
                            <div class="form-group">
                                <label for="perihal_cuti">Perihal Cuti <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="perihal_cuti" 
                                    value="<?= htmlspecialchars($cuti['perihal_cuti'] ?? '') ?>"
                                    placeholder="Contoh: Keperluan Keluarga atau Cuti Tahunan" required>
                            </div>

                            <!-- Alasan Lengkap -->
                            <div class="form-group">
                                <label for="alasan">Alasan Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="alasan" rows="4"
                                    placeholder="Jelaskan alasan Anda mengajukan cuti secara rinci" required><?= htmlspecialchars($cuti['alasan'] ?? '') ?></textarea>
                            </div>

                            <!-- Tanggal Mulai dan Berakhir -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Mulai Cuti <span class="text-danger">*</span></label>
                                        <input type="date" name="mulai" class="form-control" 
                                            value="<?= $cuti['mulai'] ?>" required>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Berakhir Cuti <span class="text-danger">*</span></label>
                                        <input type="date" name="berakhir" class="form-control" 
                                            value="<?= $cuti['berakhir'] ?>" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Tujuan -->
                            <div class="form-group">
                                <label for="tujuan">Tujuan</label>
                                <input type="text" class="form-control" name="tujuan" 
                                    value="<?= htmlspecialchars($cuti['tujuan'] ?? '') ?>"
                                    placeholder="Tujuan selama cuti (opsional)">
                            </div>

                            <!-- Berkendaraan -->
                            <div class="form-group">
                                <label for="berkendaraan">Berkendaraan</label>
                                <select class="form-control" name="berkendaraan">
                                    <option value="" <?= empty($cuti['berkendaraan']) ? 'selected' : '' ?>>Pilih Kendaraan</option>
                                    <option value="Mobil Pribadi" <?= ($cuti['berkendaraan'] == 'Mobil Pribadi') ? 'selected' : '' ?>>Mobil Pribadi</option>
                                    <option value="Motor Pribadi" <?= ($cuti['berkendaraan'] == 'Motor Pribadi') ? 'selected' : '' ?>>Motor Pribadi</option>
                                    <option value="Kendaraan Umum" <?= ($cuti['berkendaraan'] == 'Kendaraan Umum') ? 'selected' : '' ?>>Kendaraan Umum</option>
                                    <option value="Tidak Berkendara" <?= ($cuti['berkendaraan'] == 'Tidak Berkendara') ? 'selected' : '' ?>>Tidak Berkendara</option>
                                </select>
                            </div>

                            <!-- Pengikut -->
                            <div class="form-group">
                                <label for="pengikut">Pengikut</label>
                                <input type="text" class="form-control" name="pengikut" 
                                    value="<?= htmlspecialchars($cuti['pengikut'] ?? '') ?>"
                                    placeholder="Nama pengikut selama cuti (opsional)">
                            </div>

                            <!-- Keperluan -->
                            <div class="form-group">
                                <label for="keperluan">Keperluan</label>
                                <textarea class="form-control" name="keperluan" rows="3"
                                    placeholder="Keperluan khusus selama cuti (opsional)"><?= htmlspecialchars($cuti['keperluan'] ?? '') ?></textarea>
                            </div>

                            <hr>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Pengajuan
                                </button>
                                <a href="<?= base_url('Cuti/view_pegawai_menunggu') ?>" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Batal
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php $this->load->view("pegawai/components/js.php") ?>

<script>
$(document).ready(function() {
    // Validasi tanggal
    $('input[name="mulai"]').change(function() {
        const mulai = new Date($(this).val());
        const berakhir = new Date($('input[name="berakhir"]').val());
        
        if (berakhir < mulai) {
            $('input[name="berakhir"]').val($(this).val());
        }
    });

    // Tampilkan notifikasi jika ada
    <?php if ($this->session->flashdata('error')): ?>
        toastr.error('<?= $this->session->flashdata("error") ?>');
    <?php endif; ?>

    <?php if ($this->session->flashdata('success')): ?>
        toastr.success('<?= $this->session->flashdata("success") ?>');
    <?php endif; ?>
});
</script>
</body>
</html>