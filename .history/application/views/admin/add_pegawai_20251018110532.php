<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view("admin/components/header.php") ?>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view("admin/components/navbar.php") ?>
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Tambah Pegawai Baru</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('Pegawai/view_admin') ?>">Pegawai</a></li>
                                <li class="breadcrumb-item active">Tambah Pegawai</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Form Tambah Pegawai</h3>
                        </div>
                        <form action="<?= base_url('Pegawai/add_pegawai') ?>" method="POST">
                            <div class="card-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Informasi:</strong> Akun akan dibuat dengan NIP/NRP sebagai username dan password default 'kemhan2025'.
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nama_lengkap" 
                                                   placeholder="Masukkan Nama Lengkap" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NIP / NRP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nip" 
                                                   placeholder="Masukkan NIP / NRP" required 
                                                   onchange="updateUsername()">
                                            <small class="text-muted">NIP/NRP akan menjadi username untuk login</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" 
                                                   placeholder="Masukkan Email" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>No Telepon</label>
                                            <input type="text" class="form-control" name="no_telp" 
                                                   placeholder="Masukkan No Telepon">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Pangkat / Golongan</label>
                                            <select class="form-control" name="pangkat">
                                                <option value="">Pilih Pangkat / Golongan</option>
                                                <option value="Eselon I">Eselon I</option>
                                                <option value="Eselon II">Eselon II</option>
                                                <option value="Eselon III">Eselon III</option>
                                                <option value="Eselon IV">Eselon IV</option>
                                                <option value="Pelaksana">Pelaksana</option>
                                                <option value="Staf">Staf</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan</label>
                                            <input type="text" class="form-control" name="jabatan" 
                                                   placeholder="Masukkan Jabatan">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Atasan Langsung</label>
                                            <select class="form-control" name="id_atasan">
                                                <option value="">Tidak Ada Atasan</option>
                                                <?php 
                                                // Query untuk atasan (admin dan superadmin)
                                                $this->db->select('u.id_user, ud.nama_lengkap, ud.jabatan, ul.user_level');
                                                $this->db->from('user u');
                                                $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
                                                $this->db->join('user_level ul', 'u.id_user_level = ul.id_user_level');
                                                $this->db->where_in('u.id_user_level', [2, 3]);
                                                $this->db->where('u.is_active', 1);
                                                $atasan_list = $this->db->get()->result_array();
                                                foreach($atasan_list as $atasan): 
                                                ?>
                                                <option value="<?= $atasan['id_user'] ?>">
                                                    <?= $atasan['nama_lengkap'] ?> - <?= $atasan['jabatan'] ?> (<?= $atasan['user_level'] ?>)
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jenis Kelamin</label>
                                            <select class="form-control" name="id_jenis_kelamin">
                                                <option value="">Pilih Jenis Kelamin</option>
                                                <option value="1">Laki-Laki</option>
                                                <option value="2">Perempuan</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3" 
                                              placeholder="Masukkan Alamat"></textarea>
                                </div>

                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-key"></i> Informasi Login:</h6>
                                    <p class="mb-1"><strong>Username:</strong> <span id="preview_username">[Akan sama dengan NIP/NRP]</span></p>
                                    <p class="mb-0"><strong>Password Default:</strong> kemhan2025</p>
                                    <small>Pegawai dapat mengubah password setelah login pertama kali</small>
                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan Pegawai
                                </button>
                                <a href="<?= base_url('Pegawai/view_admin') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    </div>

    <?php $this->load->view("admin/components/js.php") ?>
    
    <script>
    function updateUsername() {
        const nip = document.querySelector('input[name="nip"]').value;
        const preview = document.getElementById('preview_username');
        if (nip) {
            preview.textContent = nip;
        } else {
            preview.textContent = '[Akan sama dengan NIP/NRP]';
        }
    }
    </script>
</body>
</html>
