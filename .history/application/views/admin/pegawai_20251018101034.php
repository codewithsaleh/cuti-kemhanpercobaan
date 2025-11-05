<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("admin/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    
    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('input')){ ?>
    <script>
    swal({
        title: "Success!",
        text: "Data Berhasil Ditambahkan!",
        icon: "success"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror')){ ?>
    <script>
    swal({
        title: "Erorr!",
        text: "Data Gagal Ditambahkan!",
        icon: "error"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('edit')){ ?>
    <script>
    swal({
        title: "Success!",
        text: "Data Berhasil Diedit!",
        icon: "success"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_edit')){ ?>
    <script>
    swal({
        title: "Erorr!",
        text: "Data Gagal Diedit!",
        icon: "error"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('hapus')){ ?>
    <script>
    swal({
        title: "Success!",
        text: "Data Berhasil Dihapus!",
        icon: "success"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_hapus')){ ?>
    <script>
    swal({
        title: "Erorr!",
        text: "Data Gagal Dihapus !",
        icon: "error"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('success_reset')){ ?>
    <script>
    swal({
        title: "Success!",
        text: "<?= $this->session->flashdata('success_reset') ?>",
        icon: "success"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_reset')){ ?>
    <script>
    swal({
        title: "Erorr!",
        text: "<?= $this->session->flashdata('eror_reset') ?>",
        icon: "error"
    });
    </script>
    <?php } ?>
    
    <?php if ($this->session->flashdata('import_success')){ ?>
    <script>
    swal({
        title: "Import Berhasil!",
        text: "<?= $this->session->flashdata('import_success') ?>",
        icon: "success"
    });
    </script>
    <?php } ?>
    <?php if ($this->session->flashdata('import_error')){ ?>
    <script>
    swal({
        title: "Import Gagal!",
        text: "<?= $this->session->flashdata('import_error') ?>",
        icon: "error"
    });
    </script>
    <?php } ?>

.modal-header {
            background-color: #4e73df;
            color: white;
            
    <div class="wrapper">

        <!-- Navbar -->
        <?php $this->load->view("admin/components/navbar.php") ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Manajemen Pengguna</h1>
                        </div>

                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Pengguna</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card card-table-modern">
                                <div class="card-header">
                                    <h3 class="card-title">Data Pengguna (Pegawai & Atasan)</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#import_pegawai_modal">
                                            <i class="fas fa-file-excel"></i> Impor Pegawai
                                        </button>
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                            data-target="#tambah_pegawai_modal">
                                            <i class="fas fa-plus"></i> Tambah Pegawai
                                        </button>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Nama Lengkap</th>
                                                <th>NIP / NRP</th>
                                                <th>Peran</th>
                                                <th>Sisa Cuti</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 0;
                                            foreach($users as $user_data):
                                                $no++;
                                            ?>
                                            <tr>
                                                <td><?= $no ?></td>
                                                <td><?= htmlspecialchars($user_data['username']) ?></td>
                                                <td><?= htmlspecialchars($user_data['nama_lengkap']) ?></td>
                                                <td><?= htmlspecialchars($user_data['nip']) ?></td>
                                                <td>
                                                    <?php if($user_data['id_user_level'] == 1): ?>
                                                        <span class="badge badge-secondary"><?= htmlspecialchars($user_data['user_level']) ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-info"><?= htmlspecialchars($user_data['user_level']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= htmlspecialchars($user_data['jatah_cuti']) ?> Hari</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button class="btn btn-sm btn-primary" data-toggle="modal"
                                                            data-target="#edit_data_pegawai_<?= htmlspecialchars($user_data['id_user']) ?>">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <a href="<?= base_url('Pegawai/proses_reset_password/' . $user_data['id_user']) ?>"
                                                            class="btn btn-sm btn-info" title="Reset Password"
                                                            onclick="return confirm('Anda yakin ingin mereset password untuk <?= htmlspecialchars($user_data['nama_lengkap']) ?>?')">
                                                            <i class="fas fa-key"></i>
                                                        </a>

                                                        <a href="<?= base_url('Pegawai/proses_reset_cuti_pegawai/' . $user_data['id_user']) ?>"
                                                            class="btn btn-sm btn-warning" title="Reset Cuti"
                                                            onclick="return confirm('Anda yakin ingin mereset jatah cuti untuk <?= htmlspecialchars($user_data['nama_lengkap']) ?>?')">
                                                            <i class="fas fa-sync-alt"></i>
                                                        </a>
                                                        <button data-toggle="modal" data-target="#hapus_<?= htmlspecialchars($user_data['id_user']) ?>"
                                                            class="btn btn-sm btn-danger" title="Hapus Pengguna"><i
                                                                class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- [PERBAIKAN] Memindahkan semua modal ke luar dari loop utama untuk menghindari konflik -->
        <?php foreach($users as $user_data): ?>
        <!-- Modal Hapus Data -->
        <div class="modal fade" id="hapus_<?= htmlspecialchars($user_data['id_user']) ?>" tabindex="-1"
            aria-labelledby="hapusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="hapusModalLabel">Hapus Data Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?php echo base_url()?>Pegawai/hapus_pegawai"
                            method="post">
                            <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($user_data['id_user'])?>" />
                            <p>Apakah Anda yakin ingin menghapus data <strong><?= htmlspecialchars($user_data['nama_lengkap']) ?></strong>?</p>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Pengguna -->
        <div class="modal fade" id="edit_data_pegawai_<?= htmlspecialchars($user_data['id_user']) ?>" tabindex="-1"
            aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?=base_url();?>Pegawai/edit_pegawai"
                            method="POST">
                            <input type="hidden" value="<?= htmlspecialchars($user_data['id_user']) ?>" name="id_user">
                            <input type="hidden" value="<?= htmlspecialchars($user_data['id_user_level']) ?>" name="id_user_level">

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Username</label>
                                    <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user_data['username']) ?>" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Password (Kosongkan jika tidak diubah)</label>
                                    <input type="password" class="form-control" name="password" placeholder="Masukkan password baru">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user_data['email']) ?>" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" value="<?= htmlspecialchars($user_data['nama_lengkap']) ?>" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>NIP / NRP</label>
                                    <input type="text" class="form-control" name="nip" value="<?= htmlspecialchars($user_data['nip']) ?>">
                                </div>
                            </div>
                            
                            <?php if($user_data['id_user_level'] == 1): ?>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Pangkat / Golongan</label>
                                    <select class="form-control" name="pangkat">
                                        <option value="" disabled>Pilih Pangkat / Golongan</option>
                                        <optgroup label="Golongan IV (Pembina)">
                                            <option value="Pembina Utama (IV/e)" <?= $user_data['pangkat'] == 'Pembina Utama (IV/e)' ? 'selected' : '' ?>>Pembina Utama (IV/e)</option>
                                            <option value="Pembina Utama Madya (IV/d)" <?= $user_data['pangkat'] == 'Pembina Utama Madya (IV/d)' ? 'selected' : '' ?>>Pembina Utama Madya (IV/d)</option>
                                            <option value="Pembina Utama Muda (IV/c)" <?= $user_data['pangkat'] == 'Pembina Utama Muda (IV/c)' ? 'selected' : '' ?>>Pembina Utama Muda (IV/c)</option>
                                            <option value="Pembina Tingkat I (IV/b)" <?= $user_data['pangkat'] == 'Pembina Tingkat I (IV/b)' ? 'selected' : '' ?>>Pembina Tingkat I (IV/b)</option>
                                            <option value="Pembina (IV/a)" <?= $user_data['pangkat'] == 'Pembina (IV/a)' ? 'selected' : '' ?>>Pembina (IV/a)</option>
                                        </optgroup>
                                        <optgroup label="Golongan III (Penata)">
                                            <option value="Penata Tingkat I (III/d)" <?= $user_data['pangkat'] == 'Penata Tingkat I (III/d)' ? 'selected' : '' ?>>Penata Tingkat I (III/d)</option>
                                            <option value="Penata (III/c)" <?= $user_data['pangkat'] == 'Penata (III/c)' ? 'selected' : '' ?>>Penata (III/c)</option>
                                            <option value="Penata Muda Tingkat I (III/b)" <?= $user_data['pangkat'] == 'Penata Muda Tingkat I (III/b)' ? 'selected' : '' ?>>Penata Muda Tingkat I (III/b)</option>
                                            <option value="Penata Muda (III/a)" <?= $user_data['pangkat'] == 'Penata Muda (III/a)' ? 'selected' : '' ?>>Penata Muda (III/a)</option>
                                        </optgroup>
                                        <optgroup label="TNI - Perwira Tinggi">
                                            <option value="Jenderal" <?= $user_data['pangkat'] == 'Jenderal' ? 'selected' : '' ?>>Jenderal</option>
                                            <option value="Letnan Jenderal" <?= $user_data['pangkat'] == 'Letnan Jenderal' ? 'selected' : '' ?>>Letnan Jenderal</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Jabatan</label>
                                    <select class="form-control" name="jabatan">
                                        <option value="" disabled>Pilih Jabatan</option>
                                        <option value="Staf" <?= $user_data['jabatan'] == 'Staf' ? 'selected' : '' ?>>Staf</option>
                                        <option value="Analis Kebijakan" <?= $user_data['jabatan'] == 'Analis Kebijakan' ? 'selected' : '' ?>>Analis Kebijakan</option>
                                        <option value="Lainnya" <?= $user_data['jabatan'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label>Atasan Langsung</label>
                                <select class="form-control" name="id_atasan" required>
                                    <option value="">Tidak Ada Atasan</option>
                                    <?php foreach($atasan_list as $atasan): ?>
                                        <option value="<?= htmlspecialchars($atasan['id_user']) ?>" <?= ($atasan['id_user'] == $user_data['id_atasan']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($atasan['nama_lengkap']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-control" name="id_jenis_kelamin" required>
                                        <?php foreach($jenis_kelamin_p as $jk) : ?>
                                            <option value="<?= htmlspecialchars($jk["id_jenis_kelamin"]) ?>" <?php if($jk["id_jenis_kelamin"] == $user_data['id_jenis_kelamin']) echo 'selected'; ?>>
                                                <?= htmlspecialchars($jk["jenis_kelamin"]) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>No Telp</label>
                                    <input type="text" class="form-control" name="no_telp" value="<?= htmlspecialchars($user_data['no_telp']) ?>" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3" required><?= htmlspecialchars($user_data['alamat']) ?></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>


        <!-- Modal Tambah & Impor Pegawai -->
        <div class="modal fade" id="tambah_pegawai_modal" tabindex="-1" aria-labelledby="tambahModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahModalLabel">Tambah Pegawai Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?=base_url();?>Pegawai/tambah_pegawai" method="POST">
                            <p class="text-muted">
                                Akun akan dibuat dengan <strong>NIP/NRP sebagai Username</strong> dan password default <strong>'kemhan2025'</strong>.
                            </p>
                            <hr>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Nama Lengkap</label>
                                    <input type="text" class="form-control" name="nama_lengkap" placeholder="Masukkan Nama Lengkap" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>NIP / NRP (akan menjadi username)</label>
                                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP / NRP" required>
                                </div>
                            </div>
                             <div class="form-group">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Pangkat / Golongan</label>
                                    <select class="form-control" name="pangkat">
                                        <option value="" disabled selected>Pilih Pangkat / Golongan</option>
                                        <optgroup label="Golongan IV (Pembina)">
                                            <option value="Pembina Utama (IV/e)">Pembina Utama (IV/e)</option>
                                            <option value="Pembina Utama Madya (IV/d)">Pembina Utama Madya (IV/d)</option>
                                            <option value="Pembina Utama Muda (IV/c)">Pembina Utama Muda (IV/c)</option>
                                            <option value="Pembina Tingkat I (IV/b)">Pembina Tingkat I (IV/b)</option>
                                            <option value="Pembina (IV/a)">Pembina (IV/a)</option>
                                        </optgroup>
                                        <optgroup label="Golongan III (Penata)">
                                            <option value="Penata Tingkat I (III/d)">Penata Tingkat I (III/d)</option>
                                            <option value="Penata (III/c)">Penata (III/c)</option>
                                            <option value="Penata Muda Tingkat I (III/b)">Penata Muda Tingkat I (III/b)</option>
                                            <option value="Penata Muda (III/a)">Penata Muda (III/a)</option>
                                        </optgroup>
                                        <optgroup label="TNI - Perwira Tinggi">
                                            <option value="Jenderal">Jenderal</option>
                                            <option value="Letnan Jenderal">Letnan Jenderal</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Jabatan</label>
                                    <select class="form-control" name="jabatan">
                                        <option value="" disabled selected>Pilih Jabatan</option>
                                        <option value="Staf">Staf</option>
                                        <option value="Analis Kebijakan">Analis Kebijakan</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Atasan Langsung</label>
                                <select class="form-control" name="id_atasan" required>
                                    <option value="">Tidak Ada Atasan</option>
                                    <?php foreach($atasan_list as $atasan): ?>
                                        <option value="<?= htmlspecialchars($atasan['id_user']) ?>"><?= htmlspecialchars($atasan['nama_lengkap']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Jenis Kelamin</label>
                                    <select class="form-control" name="id_jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <?php foreach($jenis_kelamin_p as $jk) : ?>
                                            <option value="<?= htmlspecialchars($jk["id_jenis_kelamin"]) ?>"><?= htmlspecialchars($jk["jenis_kelamin"]) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>No Telp</label>
                                    <input type="text" class="form-control" name="no_telp" placeholder="Masukkan No Telepon">
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3" placeholder="Masukkan Alamat"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah Akun</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="import_pegawai_modal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Impor Data Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('Pegawai/import_pegawai') ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="file_csv">Pilih File CSV</label>
                                <input type="file" name="file_csv" class="form-control-file" id="file_csv" required accept=".csv">
                            </div>
                            <hr>
                            <p><strong>Panduan Format File:</strong></p>
                            <p class="text-muted">
                                Akun akan dibuat dengan <strong>NIP/NRP sebagai Username</strong> dan password default <strong>'kemhan2025'</strong>.
                                Pastikan file .csv Anda memiliki urutan kolom sebagai berikut:
                                <br><code>email,nama_lengkap,nip,pangkat,jabatan,id_jenis_kelamin,no_telp,alamat</code>
                            </p>
                            <p class="text-muted">
                                Untuk <strong>id_jenis_kelamin</strong>, gunakan angka: <strong>1</strong> untuk Laki-laki dan <strong>2</strong> untuk Perempuan.
                            </p>
                            <a href="<?= base_url('assets/templates/template_impor_standar.csv') ?>" class="btn btn-sm btn-info" download>
                                <i class="fas fa-download"></i> Unduh Template Baru
                            </a>
                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-success">Impor Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <?php $this->load->view("admin/components/js.php") ?>
    
    <script>
    $(function() {
        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }

        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": [
                {
                    extend: 'csv',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'excel',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: ':not(:last-child)'
                    }
                }
            ]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
    </script>
</body>
</html>
