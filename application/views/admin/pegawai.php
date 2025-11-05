<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("admin/components/header.php") ?>
</head>

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


    <!-- Notifikasi -->
    <?php if ($this->session->flashdata('input')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "<?= $this->session->flashdata('input') ?>",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror')) { ?>
        <script>
            swal({
                title: "Error!",
                text: "<?= $this->session->flashdata('eror') ?>",
                icon: "error"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('edit')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "Data Berhasil Diedit!",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('edit')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "<?= $this->session->flashdata('edit') ?>",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_edit')) { ?>
        <scrip>
            swal({
            title: "Error!",
            text: "<?= $this->session->flashdata('eror_edit') ?>",
            icon: "error"
            });
        </scrip t>
    <?php } ?>
    <?php if ($this->session->flashdata('hapus')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "<?= $this->session->flashdata('hapus') ?>",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_hapus')) { ?>
        <scrip>
            swal({
            title: "Error!",
            text: "<?= $this->session->flashdata('eror_hapus') ?>",
            icon: "error"
            });
        </scrip t>
    <?php } ?>
    <?php if ($this->session->flashdata('success_reset')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "<?= $this->session->flashdata('success_reset') ?>",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('eror_reset')) { ?>
        <script>
            swal({
                title: "Erorr!",
                text: "<?= $this->session->flashdata('eror_reset') ?>",
                icon: "error"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('import_success')) { ?>
        <script>
            swal({
                title: "Import Berhasil!",
                text: "<?= $this->session->flashdata('import_success') ?>",
                icon: "success"
            });
        </script>
    <?php } ?>
    <?php if ($this->session->flashdata('import_error')) { ?>
        <script>
            swal({
                title: "Import Gagal!",
                text: "<?= $this->session->flashdata('import_error') ?>",
                icon: "error"
            });
        </script>
    <?php } ?>

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
                                            foreach ($users as $user_data):
                                                $no++;
                                                ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td><?= htmlspecialchars($user_data['username']) ?></td>
                                                    <td><?= htmlspecialchars($user_data['nama_lengkap']) ?></td>
                                                    <td><?= htmlspecialchars($user_data['nip']) ?></td>
                                                    <td>
                                                        <?php if ($user_data['id_user_level'] == 1): ?>
                                                            <span
                                                                class="badge badge-secondary"><?= htmlspecialchars($user_data['user_level']) ?></span>
                                                        <?php else: ?>
                                                            <span
                                                                class="badge badge-info"><?= htmlspecialchars($user_data['user_level']) ?></span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <strong><?= htmlspecialchars($user_data['sisa_cuti']) ?>
                                                            Hari</strong>
                                                        <br>
                                                        <small class="text-muted">Dari
                                                            <?= htmlspecialchars($user_data['jatah_cuti']) ?> Hari</small>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-primary" title="Edit Data"
                                                                data-toggle="modal"
                                                                data-target="#edit_data_pegawai_<?= htmlspecialchars($user_data['id_user']) ?>">
                                                                <i class="fas fa-edit"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-info"
                                                                title="Reset Password" data-toggle="modal"
                                                                data-target="#reset_password<?= htmlspecialchars($user_data['id_user']) ?>">
                                                                <i class="fas fa-key"></i>
                                                            </button>

                                                            <button type="button" class="btn btn-sm btn-warning"
                                                                title="Reset Cuti" data-toggle="modal"
                                                                data-target="#reset_cuti<?= htmlspecialchars($user_data['id_user']) ?>">
                                                                <i class="fas fa-sync-alt"></i>
                                                            </button>

                                                            <button data-toggle="modal"
                                                                data-target="#hapus_pengguna<?= htmlspecialchars($user_data['id_user']) ?>"
                                                                class="btn btn-sm btn-danger" title="Hapus Pengguna"><i
                                                                    class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
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

        <!-- [PERBAIKAN] Memindahkan semua modal ke luar dari loop utama untuk menghindari konflik -->
        <?php foreach ($users as $user_data): ?>

            <!-- Modal Hapus Data -->
            <div class="modal fade" id="hapus_pengguna<?= htmlspecialchars($user_data['id_user']) ?>" tabindex="-1"
                aria-labelledby="hapusModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="hapusModalLabel">Hapus User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('Pegawai/hapus_pegawai') ?>" method="POST"
                                id="formHapusUser_<?= $user_data['id_user'] ?>">
                                <input type="hidden" name="id_user"
                                    value="<?= htmlspecialchars($user_data['id_user']) ?>" />

                                <div class="text-center mb-3">
                                    <i class="fas fa-exclamation-triangle text-warning fa-3x"></i>
                                </div>

                                <p class=" text-center">Apakah Anda yakin ingin menghapus user:</p>
                                <h5 class="text-center text-danger">
                                    <?= htmlspecialchars($user_data['nama_lengkap']) ?>
                                </h5>
                                <p class="text-center text-muted">NIP:
                                    <?= htmlspecialchars($user_data['nip']) ?>
                                </p>

                                <div class="alert alert-warning">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Perhatian:</strong> Tindakan ini tidak dapat dibatalkan.
                                        Semua data yang terkait dengan user ini akan dihapus.
                                    </small>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Ya, Hapus
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                // Auto close modal setelah submit
                document.getElementById('formHapusUser_<?= $user_data['id_user'] ?>').addEventListener('submit', function () {
                    $('#hapus_pengguna<?= $user_data['id_user'] ?>').modal('hide');
                });
            </script>

            <!-- Modal Reset Cuti -->
            <div class="modal fade" id="reset_cuti<?= htmlspecialchars($user_data['id_user']) ?>" tabindex="-1"
                aria-labelledby="resetCutiModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resetCutiModalLabel">Reset Jatah Cuti</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('Pegawai/proses_reset_cuti_pegawai/' . $user_data['id_user']) ?>"
                                method="POST" id="formResetCuti_<?= $user_data['id_user'] ?>">
                                <input type="hidden" name="id_user"
                                    value="<?= htmlspecialchars($user_data['id_user']) ?>" />

                                <div class="text-center mb-3">
                                    <i class="fas fa-sync-alt text-warning fa-3x"></i>
                                </div>

                                <p class="text-center">Apakah Anda yakin ingin mereset jatah cuti untuk:</p>
                                <h5 class="text-center text-warning">
                                    <?= htmlspecialchars($user_data['nama_lengkap']) ?>
                                </h5>
                                <p class="text-center text-muted">NIP:
                                    <?= htmlspecialchars($user_data['nip']) ?>
                                </p>

                                <div class="alert alert-warning">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Informasi:</strong> Jatah cuti akan direset ke <strong>12 hari</strong>
                                        untuk tahun
                                        <strong><?= date('Y') ?></strong>.
                                        Reset cuti akan memperbarui sisa cuti yang tersedia.
                                    </small>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-sync-alt"></i> Ya, Reset Cuti
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Reset Password -->
            <div class="modal fade" id="reset_password<?= htmlspecialchars($user_data['id_user']) ?>" tabindex="-1"
                aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url('Pegawai/proses_reset_password/' . $user_data['id_user']) ?>"
                                method="POST" id="formResetPassword_<?= $user_data['id_user'] ?>">

                                <!-- Tambahkan CSRF Token untuk security -->
                                <input type="hidden" name="<?= $this->security->get_csrf_token_name() ?>"
                                    value="<?= $this->security->get_csrf_hash() ?>" />
                                <input type="hidden" name="id_user"
                                    value="<?= htmlspecialchars($user_data['id_user']) ?>" />

                                <div class="text-center mb-3">
                                    <i class="fas fa-key text-info fa-3x"></i>
                                </div>

                                <p class="text-center">Apakah Anda yakin ingin mereset password untuk:</p>
                                <h5 class="text-center text-primary">
                                    <?= htmlspecialchars($user_data['nama_lengkap']) ?>
                                </h5>
                                <p class="text-center text-muted">NIP:
                                    <?= htmlspecialchars($user_data['nip']) ?>
                                </p>

                                <div class="alert alert-warning">
                                    <small>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Perubahan Sistem Keamanan:</strong> Password sekarang dienkripsi dengan
                                        <strong>algoritma bcrypt</strong> yang lebih aman.
                                    </small>
                                </div>

                                <div class="alert alert-info">
                                    <small>
                                        <i class="fas fa-info-circle"></i>
                                        <strong>Informasi:</strong> Password akan direset ke default
                                        (<strong>kemhan2025</strong>).
                                        User disarankan mengubah password setelah login.
                                    </small>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-info">
                                        <i class="fas fa-sync-alt"></i> Ya, Reset Password
                                    </button>
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
                            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="<?= base_url(); ?>Pegawai/edit_pegawai" method="POST"
                                id="formEditUser_<?= $user_data['id_user'] ?>">
                                <input type="hidden" value="<?= htmlspecialchars($user_data['id_user']) ?>" name="id_user">
                                <input type="hidden" value="<?= htmlspecialchars($user_data['id_user_level']) ?>"
                                    name="id_user_level">

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Username <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="username"
                                            value="<?= htmlspecialchars($user_data['username']) ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Password (Kosongkan jika tidak diubah)</label>
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Masukkan password baru">
                                        <small class="text-muted">Minimal 6 karakter</small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?= htmlspecialchars($user_data['email']) ?>" required>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nama_lengkap"
                                            value="<?= htmlspecialchars($user_data['nama_lengkap']) ?>" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>NIP <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="nip"
                                            value="<?= htmlspecialchars($user_data['nip']) ?>" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Pangkat / Golongan</label>
                                        <select class="form-control" name="pangkat">
                                            <option value="" disabled>Pilih Pangkat / Golongan</option>
                                            <optgroup label="Golongan IV (Pembina)">
                                                <option value="Pembina Utama (IV/e)" <?= $user_data['pangkat'] == 'Pembina Utama (IV/e)' ? 'selected' : '' ?>>Pembina Utama (IV/e)</option>
                                                <option value="Pembina Utama Madya (IV/d)"
                                                    <?= $user_data['pangkat'] == 'Pembina Utama Madya (IV/d)' ? 'selected' : '' ?>>Pembina Utama Madya (IV/d)</option>
                                                <option value="Pembina Utama Muda (IV/c)" <?= $user_data['pangkat'] == 'Pembina Utama Muda (IV/c)' ? 'selected' : '' ?>>Pembina Utama Muda (IV/c)
                                                </option>
                                                <option value="Pembina Tingkat I (IV/b)" <?= $user_data['pangkat'] == 'Pembina Tingkat I (IV/b)' ? 'selected' : '' ?>>Pembina Tingkat I (IV/b)</option>
                                                <option value="Pembina (IV/a)" <?= $user_data['pangkat'] == 'Pembina (IV/a)' ? 'selected' : '' ?>>Pembina (IV/a)</option>
                                            </optgroup>
                                            <optgroup label="Golongan III (Penata)">
                                                <option value="Penata Tingkat I (III/d)" <?= $user_data['pangkat'] == 'Penata Tingkat I (III/d)' ? 'selected' : '' ?>>Penata Tingkat I (III/d)
                                                </option>
                                                <option value="Penata (III/c)" <?= $user_data['pangkat'] == 'Penata (III/c)' ? 'selected' : '' ?>>Penata (III/c)</option>
                                                <option value="Penata Muda Tingkat I (III/b)"
                                                    <?= $user_data['pangkat'] == 'Penata Muda Tingkat I (III/b)' ? 'selected' : '' ?>>Penata Muda Tingkat I (III/b)</option>
                                                <option value="Penata Muda (III/a)" <?= $user_data['pangkat'] == 'Penata Muda (III/a)' ? 'selected' : '' ?>>Penata Muda (III/a)</option>
                                            </optgroup>
                                            <optgroup label="TNI - Perwira Tinggi">
                                                <option value="Jenderal" <?= $user_data['pangkat'] == 'Jenderal' ? 'selected' : '' ?>>Jenderal</option>
                                                <option value="Letnan Jenderal" <?= $user_data['pangkat'] == 'Letnan Jenderal' ? 'selected' : '' ?>>Letnan Jenderal</option>
                                                <option value="Mayor Jenderal" <?= $user_data['pangkat'] == 'Mayor Jenderal' ? 'selected' : '' ?>>Mayor Jenderal</option>
                                                <option value="Brigadir Jenderal" <?= $user_data['pangkat'] == 'Brigadir Jenderal' ? 'selected' : '' ?>>Brigadir Jenderal</option>
                                            </optgroup>
                                            <option value="Lainnya" <?= $user_data['pangkat'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>Jabatan <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="jabatan"
                                            value="<?= htmlspecialchars($user_data['jabatan']) ?>" required>
                                    </div>
                                </div>

                                <!-- Field Atasan (Hanya untuk Pegawai) -->
                                <?php if ($user_data['id_user_level'] == 1): ?>
                                    <div class="form-group">
                                        <label>Atasan Langsung <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_atasan" required>
                                            <option value="">Pilih Atasan Langsung</option>
                                            <?php
                                            // Query untuk atasan (hanya super admin - level 3)
                                            $this->db->select('u.id_user, ud.nama_lengkap, ud.jabatan');
                                            $this->db->from('user u');
                                            $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
                                            $this->db->where('u.id_user_level', 3); // Hanya Super Admin
                                            $this->db->where('u.is_active', 1);
                                            $atasan_list = $this->db->get()->result_array();

                                            foreach ($atasan_list as $atasan):
                                                ?>
                                                <option value="<?= $atasan['id_user'] ?>"
                                                    <?= ($atasan['id_user'] == $user_data['id_atasan']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($atasan['nama_lengkap']) ?> -
                                                    <?= htmlspecialchars($atasan['jabatan']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-6 form-group">
                                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-control" name="id_jenis_kelamin" required>
                                            <option value="" disabled>Pilih Jenis Kelamin</option>
                                            <?php foreach ($jenis_kelamin_p as $jk): ?>
                                                <option value="<?= htmlspecialchars($jk["id_jenis_kelamin"]) ?>"
                                                    <?= ($jk["id_jenis_kelamin"] == $user_data['id_jenis_kelamin']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($jk["jenis_kelamin"]) ?>
                                                </option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label>No Telepon</label>
                                        <input type="text" class="form-control" name="no_telp"
                                            value="<?= htmlspecialchars($user_data['no_telp']) ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3"
                                        placeholder="Masukkan Alamat"><?= htmlspecialchars($user_data['alamat']) ?></textarea>
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

            <script>
                // Auto close modal setelah submit
                document.getElementById('formEditUser_<?= $user_data['id_user'] ?>').addEventListener('submit', function () {
                    $('#edit_data_pegawai_<?= $user_data['id_user'] ?>').modal('hide');
                });
            </script>
        <?php endforeach; ?>


        <!-- Modal Tambah Pegawai  -->
        <div class="modal fade" id="tambah_pegawai_modal" tabindex="-1" aria-labelledby="tambahModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahModalLabel">Tambah User Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url(); ?>Pegawai/tambah_pegawai" method="POST" id="formTambahUser">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Informasi:</strong> Akun akan dibuat dengan <strong>NIP sebagai
                                    Username</strong> dan password default <strong>'kemhan2025'</strong>.
                            </div>

                            <!-- Tipe User -->
                            <div class="form-group">
                                <label>Tipe User <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_user_level" id="id_user_level" required
                                    onchange="toggleAtasanField()">
                                    <option value="">Pilih Tipe User</option>
                                    <option value="1">Pegawai</option>
                                    <option value="2">Admin</option>
                                    <option value="3">Super Admin (Atasan)</option>
                                </select>
                                <small class="text-muted">
                                    - Pegawai: Membutuhkan atasan langsung<br>
                                    - Admin & Super Admin: Tidak membutuhkan atasan
                                </small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap"
                                        placeholder="Masukkan Nama Lengkap" required>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nip" placeholder="Masukkan NIP"
                                        required>
                                    <small class="text-muted">NIP akan menjadi username untuk login</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" placeholder="Masukkan Email"
                                    required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Pangkat / Golongan</label>
                                    <select class="form-control" name="pangkat">
                                        <option value="" disabled selected>Pilih Pangkat / Golongan</option>
                                        <optgroup label="Golongan IV (Pembina)">
                                            <option value="Pembina Utama (IV/e)">Pembina Utama (IV/e)</option>
                                            <option value="Pembina Utama Madya (IV/d)">Pembina Utama Madya (IV/d)
                                            </option>
                                            <option value="Pembina Utama Muda (IV/c)">Pembina Utama Muda (IV/c)</option>
                                            <option value="Pembina Tingkat I (IV/b)">Pembina Tingkat I (IV/b)</option>
                                            <option value="Pembina (IV/a)">Pembina (IV/a)</option>
                                        </optgroup>
                                        <optgroup label="Golongan III (Penata)">
                                            <option value="Penata Tingkat I (III/d)">Penata Tingkat I (III/d)</option>
                                            <option value="Penata (III/c)">Penata (III/c)</option>
                                            <option value="Penata Muda Tingkat I (III/b)">Penata Muda Tingkat I (III/b)
                                            </option>
                                            <option value="Penata Muda (III/a)">Penata Muda (III/a)</option>
                                        </optgroup>
                                        <optgroup label="TNI - Perwira Tinggi">
                                            <option value="Jenderal">Jenderal</option>
                                            <option value="Letnan Jenderal">Letnan Jenderal</option>
                                            <option value="Mayor Jenderal">Mayor Jenderal</option>
                                            <option value="Brigadir Jenderal">Brigadir Jenderal</option>
                                        </optgroup>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>Jabatan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="jabatan"
                                        placeholder="Masukkan Jabatan" required>
                                </div>
                            </div>

                            <!-- Field Atasan (Hanya untuk Pegawai) -->
                            <div class="form-group" id="atasan_field" style="display: none;">
                                <label>Atasan Langsung <span class="text-danger">*</span></label>
                                <select class="form-control" name="id_atasan" id="id_atasan">
                                    <option value="">Pilih Atasan Langsung</option>
                                    <?php
                                    // Query untuk atasan (hanya super admin - level 3)
                                    $this->db->select('u.id_user, ud.nama_lengkap, ud.jabatan');
                                    $this->db->from('user u');
                                    $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
                                    $this->db->where('u.id_user_level', 3); // Hanya Super Admin
                                    $this->db->where('u.is_active', 1);
                                    $atasan_list = $this->db->get()->result_array();

                                    foreach ($atasan_list as $atasan):
                                        ?>
                                        <option value="<?= $atasan['id_user'] ?>">
                                            <?= htmlspecialchars($atasan['nama_lengkap']) ?> -
                                            <?= htmlspecialchars($atasan['jabatan']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Pilih atasan langsung dari daftar Super Admin yang
                                    tersedia</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label>Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-control" name="id_jenis_kelamin" required>
                                        <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                        <?php foreach ($jenis_kelamin_p as $jk): ?>
                                            <option value="<?= htmlspecialchars($jk["id_jenis_kelamin"]) ?>">
                                                <?= htmlspecialchars($jk["jenis_kelamin"]) ?>
                                            </option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label>No Telepon</label>
                                    <input type="text" class="form-control" name="no_telp"
                                        placeholder="Masukkan No Telepon">
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" rows="3"
                                    placeholder="Masukkan Alamat"></textarea>
                            </div>

                            <div class="alert alert-warning">
                                <h6><i class="fas fa-key"></i> Informasi Login:</h6>
                                <p class="mb-1"><strong>Username:</strong> <span id="preview_username">[Sama dengan
                                        NIP]</span></p>
                                <p class="mb-0"><strong>Password Default:</strong> kemhan2025</p>
                                <small>User dapat mengubah password setelah login pertama kali</small>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Tambah User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function toggleAtasanField() {
                const userLevel = document.getElementById('id_user_level').value;
                const atasanField = document.getElementById('atasan_field');
                const atasanSelect = document.getElementById('id_atasan');

                if (userLevel == '1') { // Pegawai
                    atasanField.style.display = 'block';
                    atasanSelect.required = true;
                } else { // Super Admin
                    atasanField.style.display = 'none';
                    atasanSelect.required = false;
                    atasanSelect.value = ''; // Reset value
                }
            }

            // Update username preview
            document.querySelector('input[name="nip"]').addEventListener('input', function () {
                document.getElementById('preview_username').textContent = this.value || '[Sama dengan NIP]';
            });

            // Form validation sebelum submit
            document.getElementById('formTambahUser').addEventListener('submit', function (e) {
                const userLevel = document.getElementById('id_user_level').value;
                const atasanSelect = document.getElementById('id_atasan');

                // Validasi khusus untuk pegawai
                if (userLevel == '1' && !atasanSelect.value) {
                    e.preventDefault();
                    alert('Untuk tipe user Pegawai, harap pilih atasan langsung.');
                    atasanSelect.focus();
                    return false;
                }

                // Auto close modal setelah submit
                $('#tambah_pegawai_modal').modal('hide');
            });

            // Initialize on modal show
            $('#tambah_pegawai_modal').on('show.bs.modal', function () {
                toggleAtasanField();
                document.getElementById('preview_username').textContent = document.querySelector('input[name="nip"]').value || '[Sama dengan NIP]';
            });

            // Reset form ketika modal ditutup
            $('#tambah_pegawai_modal').on('hidden.bs.modal', function () {
                document.getElementById('formTambahUser').reset();
                toggleAtasanField();
                document.getElementById('preview_username').textContent = '[Sama dengan NIP]';
            });
        </script>

        <!-- Modal Import Pegawai -->
        <div class="modal fade" id="import_pegawai_modal" tabindex="-1" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Impor Data Pengguna</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="<?= base_url('Pegawai/import_pegawai') ?>" method="post"
                            enctype="multipart/form-data" id="importForm">
                            <div class="form-group">
                                <label for="file_csv">Pilih File CSV</label>
                                <input type="file" name="file_csv" class="form-control-file" id="file_csv" required
                                    accept=".csv">
                                <small class="form-text text-muted">Maksimal 2MB, format .csv</small>
                            </div>

                            <div class="alert alert-info">
                                <small>
                                    <strong>Format CSV:</strong> email, nama_lengkap, nip, pangkat, jabatan,
                                    id_jenis_kelamin, no_telp, alamat, id_user_level<br>
                                    <strong>Password default:</strong> kemhan2025<br>
                                </small>
                            </div>

                            <div class="modal-footer">
                                <a href="<?= base_url('assets/templates/template_impor_standar.csv') ?>"
                                    class="btn btn-info btn-sm" download>
                                    <i class="fas fa-download"></i> Unduh Template CSV
                                </a>
                                <div>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                    <button type="submit" form="importForm" class="btn btn-success">Impor Data</button>
                                </div>
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
        $(function () {
            if ($.fn.DataTable.isDataTable('#example1')) {
                $('#example1').DataTable().destroy();
            }

            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                // "buttons": [
                //     {
                //         extend: 'csv',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     },
                //     {
                //         extend: 'excel',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     },
                //     {
                //         extend: 'pdf',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     },
                //     {
                //         extend: 'print',
                //         exportOptions: {
                //             columns: ':not(:last-child)'
                //         }
                //     }
                // ]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });

        document.getElementById('importForm').addEventListener('submit', function (e) {
            const fileInput = document.getElementById('file_csv');
            const file = fileInput.files[0];

            if (file) {
                // Check file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    alert('File size must be less than 2MB');
                    return;
                }

                // Check file extension
                if (!file.name.toLowerCase().endsWith('.csv')) {
                    e.preventDefault();
                    alert('Please select a CSV file');
                    return;
                }
            }
        });
    </script>


</body>

</html>