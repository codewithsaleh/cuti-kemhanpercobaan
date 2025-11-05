<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <?php if ($this->session->flashdata('input')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "Permohonan Cuti Berhasil Diajukan!",
                icon: "success"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('eror_input')) { ?>
        <script>
            swal({
                title: "Error!",
                text: "Permohonan Cuti Gagal Diajukan!",
                icon: "error"
            });
        </script>
    <?php } ?>

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
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
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
                                    <h3 class="card-title">Isi Data Pengajuan</h3>
                                </div>

                                <div class="card-body">
                                    <form action="<?= site_url('Cuti/add_cuti_pegawai') ?>" method="POST">
                                        <input type="hidden" name="id_user"
                                            value="<?= $this->session->userdata('id_user') ?>">

                                        <!-- Jenis Cuti -->
                                        <div class="form-group">
                                            <label for="id_jenis_cuti">Jenis Cuti</label>
                                            <select class="form-control" id="id_jenis_cuti" name="id_jenis_cuti"
                                                required>
                                                <option value="" disabled selected>Pilih Jenis Cuti</option>
                                                <?php foreach ($jenis_cuti as $jc): ?>
                                                    <option value="<?= $jc["id_jenis_cuti"] ?>"><?= $jc["nama_cuti"] ?>
                                                    </option>
                                                <?php endforeach ?>
                                            </select>
                                        </div>

                                        <!-- Perihal Cuti -->
                                        <div class="form-group">
                                            <label for="perihal_cuti">Perihal Cuti</label>
                                            <input type="text" class="form-control" name="perihal_cuti"
                                                placeholder="Contoh: Keperluan Keluarga atau Cuti Tahunan" required>
                                        </div>

                                        <!-- Alasan Lengkap -->
                                        <div class="form-group">
                                            <label for="alasan">Alasan Lengkap</label>
                                            <textarea class="form-control" name="alasan" rows="4"
                                                placeholder="Jelaskan alasan Anda mengajukan cuti secara rinci"
                                                required></textarea>
                                        </div>

                                        <!-- Tanggal Mulai dan Berakhir -->
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Mulai Cuti</label>
                                                    <input type="date" name="mulai" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Berakhir Cuti</label>
                                                    <input type="date" name="berakhir" class="form-control" required>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tujuan -->
                                        <div class="form-group">
                                            <label for="tujuan">Tujuan</label>
                                            <input type="text" class="form-control" name="tujuan"
                                                placeholder="Tujuan selama cuti (opsional)">
                                        </div>

                                        <!-- Berkendaraan -->
                                        <div class="form-group">
                                            <label for="berkendaraan">Berkendaraan</label>
                                            <select class="form-control" name="berkendaraan">
                                                <option value="" selected>Pilih Kendaraan</option>
                                                <option value="Mobil Pribadi">Mobil Pribadi</option>
                                                <option value="Motor Pribadi">Motor Pribadi</option>
                                                <option value="Kendaraan Umum">Kendaraan Umum</option>
                                                <option value="Tidak Berkendara">Tidak Berkendara</option>
                                            </select>
                                        </div>

                                        <!-- Pengikut -->
                                        <div class="form-group">
                                            <label for="pengikut">Pengikut</label>
                                            <input type="text" class="form-control" name="pengikut"
                                                placeholder="Nama pengikut selama cuti (opsional)">
                                        </div>

                                        <!-- Keperluan -->
                                        <div class="form-group">
                                            <label for="keperluan">Keperluan</label>
                                            <textarea class="form-control" name="keperluan" rows="3"
                                                placeholder="Keperluan khusus selama cuti (opsional)"></textarea>
                                        </div>

                                        <hr>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Ajukan Permohonan
                                        </button>
                                        <a href="<?= site_url('Dashboard/dashboard_pegawai') ?>"
                                            class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Batal
                                        </a>
                                    </form>
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

</body>

</html>