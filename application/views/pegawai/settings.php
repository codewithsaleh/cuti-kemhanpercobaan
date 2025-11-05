<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>

    <style>
        .password-criteria li {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .password-criteria i {
            margin-right: 5px;
            width: 15px;
        }

        .progress {
            background-color: #e9ecef;
            border-radius: 4px;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .toggle-password {
            cursor: pointer;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-left: none;
        }

        .toggle-password:hover {
            background-color: #e9ecef;
        }

        .toggle-password.active {
            background-color: #007bff;
            color: white;
        }

        .input-group-text {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <?php if ($this->session->flashdata('success_lengkapi')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "Data Diri Berhasil Diperbarui!",
                icon: "success"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('eror_lengkapi')) { ?>
        <script>
            swal({
                title: "Erorr!",
                text: "Data Diri Gagal Diperbarui!",
                icon: "error"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('success_password')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "Password Berhasil Diubah!",
                icon: "success"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('error_password')) { ?>
        <script>
            swal({
                title: "Erorr!",
                text: "Password Gagal Diubah!",
                icon: "error"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('password_err')) { ?>
        <script>
            swal({
                title: "Error Password!",
                text: "Ketik Ulang Password!",
                icon: "error"
            });
        </script>
    <?php } ?>

    <div class="wrapper">

        <!-- Navbar -->
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Pengaturan Akun Pegawai</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Settings</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Kolom Lengkapi Data Diri -->
                        <div class="col-md-7">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-user-circle me-1"></i> Data Diri
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url(); ?>Settings/lengkapi_data_pegawai" method="POST">
                                        <input type="hidden" name="id_user" value="<?= $pegawai['id_user'] ?>">
                                        <input type="hidden" name="id_user_detail"
                                            value="<?= $pegawai['id_user_detail'] ?>">

                                        <!-- [BARU] Username (NIP/NRP) dengan keterangan -->
                                        <div class="form-group">
                                            <label for="username">Username (NIP/NRP)</label>
                                            <input type="text" class="form-control" name="username"
                                                value="<?= $pegawai['username'] ?>" readonly>
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i> Username tidak dapat diubah karena
                                                digunakan untuk login
                                            </small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nama_lengkap">Nama Lengkap <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="nama_lengkap"
                                                        value="<?= $pegawai['nama_lengkap'] ?>" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <!-- [BARU] Field Email -->
                                                <div class="form-group">
                                                    <label for="email">Email <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email"
                                                        value="<?= $pegawai['email'] ?>" required>
                                                    <small class="text-muted">Email digunakan untuk notifikasi dan reset
                                                        password</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="nip">NIP / NRP</label>
                                                    <input type="text" class="form-control" name="nip"
                                                        value="<?= $pegawai['nip'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="pangkat">Pangkat / Golongan</label>
                                                    <select class="form-control" name="pangkat">
                                                        <option value="" disabled>Pilih Pangkat / Golongan</option>
                                                        <optgroup label="Golongan IV (Pembina)">
                                                            <option value="Pembina Utama (IV/e)"
                                                                <?= $pegawai['pangkat'] == 'Pembina Utama (IV/e)' ? 'selected' : '' ?>>Pembina Utama (IV/e)</option>
                                                            <option value="Pembina Utama Madya (IV/d)"
                                                                <?= $pegawai['pangkat'] == 'Pembina Utama Madya (IV/d)' ? 'selected' : '' ?>>Pembina Utama Madya (IV/d)</option>
                                                            <option value="Pembina Utama Muda (IV/c)"
                                                                <?= $pegawai['pangkat'] == 'Pembina Utama Muda (IV/c)' ? 'selected' : '' ?>>Pembina Utama Muda (IV/c)</option>
                                                            <option value="Pembina Tingkat I (IV/b)"
                                                                <?= $pegawai['pangkat'] == 'Pembina Tingkat I (IV/b)' ? 'selected' : '' ?>>Pembina Tingkat I (IV/b)</option>
                                                            <option value="Pembina (IV/a)"
                                                                <?= $pegawai['pangkat'] == 'Pembina (IV/a)' ? 'selected' : '' ?>>Pembina (IV/a)</option>
                                                        </optgroup>
                                                        <optgroup label="Golongan III (Penata)">
                                                            <option value="Penata Tingkat I (III/d)"
                                                                <?= $pegawai['pangkat'] == 'Penata Tingkat I (III/d)' ? 'selected' : '' ?>>Penata Tingkat I (III/d)</option>
                                                            <option value="Penata (III/c)"
                                                                <?= $pegawai['pangkat'] == 'Penata (III/c)' ? 'selected' : '' ?>>Penata (III/c)</option>
                                                            <option value="Penata Muda Tingkat I (III/b)"
                                                                <?= $pegawai['pangkat'] == 'Penata Muda Tingkat I (III/b)' ? 'selected' : '' ?>>Penata Muda Tingkat I (III/b)
                                                            </option>
                                                            <option value="Penata Muda (III/a)"
                                                                <?= $pegawai['pangkat'] == 'Penata Muda (III/a)' ? 'selected' : '' ?>>Penata Muda (III/a)</option>
                                                        </optgroup>
                                                        <optgroup label="TNI - Perwira Tinggi">
                                                            <option value="Jenderal" <?= $pegawai['pangkat'] == 'Jenderal' ? 'selected' : '' ?>>Jenderal</option>
                                                            <option value="Letnan Jenderal"
                                                                <?= $pegawai['pangkat'] == 'Letnan Jenderal' ? 'selected' : '' ?>>Letnan Jenderal</option>
                                                            <option value="Mayor Jenderal"
                                                                <?= $pegawai['pangkat'] == 'Mayor Jenderal' ? 'selected' : '' ?>>Mayor Jenderal</option>
                                                            <option value="Brigadir Jenderal"
                                                                <?= $pegawai['pangkat'] == 'Brigadir Jenderal' ? 'selected' : '' ?>>Brigadir Jenderal</option>
                                                        </optgroup>
                                                        <option value="Lainnya" <?= $pegawai['pangkat'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="jabatan">Jabatan <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="jabatan"
                                                        value="<?= $pegawai['jabatan'] ?>" required
                                                        placeholder="Masukkan Jabatan">
                                                    <small class="text-muted">Contoh: Staf Administrasi, Analis
                                                        Kebijakan, dll.</small>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="id_jenis_kelamin">Jenis Kelamin <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" name="id_jenis_kelamin" required>
                                                        <?php foreach ($jenis_kelamin as $jk): ?>
                                                            <option value="<?= $jk["id_jenis_kelamin"] ?>"
                                                                <?= $jk["id_jenis_kelamin"] == $pegawai['id_jenis_kelamin'] ? 'selected' : '' ?>>
                                                                <?= $jk["jenis_kelamin"] ?>
                                                            </option>
                                                        <?php endforeach ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="no_telp">No. Telepon / HP</label>
                                                    <input type="text" class="form-control" name="no_telp"
                                                        value="<?= $pegawai['no_telp'] ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="alamat">Alamat</label>
                                                    <textarea class="form-control" name="alamat"
                                                        rows="1"><?= $pegawai['alamat'] ?></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Data Diri
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Ganti Password -->
                        <div class="col-md-5">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fa fa-key me-1"></i> Ganti Password
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form action="<?= base_url(); ?>Settings/settings_account_pegawai" method="POST"
                                        id="passwordForm">
                                        <!-- [BARU] Password Saat Ini -->
                                        <div class="form-group">
                                            <label for="current_password">Password Saat Ini <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="current_password"
                                                    name="current_password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="current_password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Masukkan password saat ini untuk
                                                verifikasi</small>
                                        </div>

                                        <!-- Password Baru -->
                                        <div class="form-group">
                                            <label for="password">Password Baru <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="password"
                                                    name="password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- [BARU] Status Kekuatan Password -->
                                            <div class="password-strength mt-2">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar" id="password-strength-bar"
                                                        role="progressbar" style="width: 0%"></div>
                                                </div>
                                                <small id="password-strength-text" class="form-text"></small>
                                            </div>

                                            <!-- [BARU] Kriteria Password -->
                                            <div class="password-criteria mt-2">
                                                <small class="text-muted">Kriteria password:</small>
                                                <ul class="list-unstyled mb-0">
                                                    <li id="criteria-length"><i class="fas fa-times text-danger"></i>
                                                        Minimal 8 karakter</li>
                                                    <li id="criteria-uppercase"><i class="fas fa-times text-danger"></i>
                                                        Mengandung huruf besar (A-Z)</li>
                                                    <li id="criteria-lowercase"><i class="fas fa-times text-danger"></i>
                                                        Mengandung huruf kecil (a-z)</li>
                                                    <li id="criteria-number"><i class="fas fa-times text-danger"></i>
                                                        Mengandung angka (0-9)</li>
                                                    <li id="criteria-special"><i class="fas fa-times text-danger"></i>
                                                        Mengandung karakter khusus (!@#$%)</li>
                                                    <li id="criteria-username"><i class="fas fa-times text-danger"></i>
                                                        Tidak sama dengan username</li>
                                                    <li id="criteria-year"><i class="fas fa-times text-danger"></i>
                                                        Tidak mengandung tahun berjalan</li>
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Konfirmasi Password Baru -->
                                        <div class="form-group">
                                            <label for="re_password">Ulangi Password Baru <span
                                                    class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="re_password"
                                                    name="re_password" required>
                                                <div class="input-group-append">
                                                    <span class="input-group-text toggle-password"
                                                        data-target="re_password">
                                                        <i class="fas fa-eye"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <small id="password-match" class="form-text"></small>
                                        </div>

                                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                                            <i class="fas fa-save"></i> Simpan Password
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <!-- ./wrapper -->

    <?php $this->load->view("pegawai/components/js.php") ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const rePasswordInput = document.getElementById('re_password');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            const submitBtn = document.getElementById('submitBtn');
            const username = '<?= $this->session->userdata("username") ?>'; // Ambil username dari session

            // Criteria elements
            const criteriaElements = {
                length: document.getElementById('criteria-length'),
                uppercase: document.getElementById('criteria-uppercase'),
                lowercase: document.getElementById('criteria-lowercase'),
                number: document.getElementById('criteria-number'),
                special: document.getElementById('criteria-special'),
                username: document.getElementById('criteria-username'),
                year: document.getElementById('criteria-year')
            };

            // [BARU] Toggle Password Functionality
            document.querySelectorAll('.toggle-password').forEach(toggle => {
                toggle.addEventListener('click', function () {
                    const targetId = this.getAttribute('data-target');
                    const targetInput = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (targetInput.type === 'password') {
                        targetInput.type = 'text';
                        icon.className = 'fas fa-eye-slash';
                        this.classList.add('active');
                    } else {
                        targetInput.type = 'password';
                        icon.className = 'fas fa-eye';
                        this.classList.remove('active');
                    }
                });
            });

            passwordInput.addEventListener('input', checkPasswordStrength);
            rePasswordInput.addEventListener('input', checkPasswordMatch);

            function checkPasswordStrength() {
                const password = passwordInput.value;

                // Check criteria
                const criteria = {
                    length: password.length >= 8,
                    uppercase: /[A-Z]/.test(password),
                    lowercase: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[!@#$%]/.test(password),
                    username: password.toLowerCase() !== username.toLowerCase(),
                    year: !password.includes(new Date().getFullYear().toString())
                };

                // Update criteria icons
                Object.keys(criteria).forEach(key => {
                    const icon = criteriaElements[key].querySelector('i');
                    if (criteria[key]) {
                        icon.className = 'fas fa-check text-success';
                    } else {
                        icon.className = 'fas fa-times text-danger';
                    }
                });

                // Calculate strength
                const score = Object.values(criteria).filter(Boolean).length;
                const total = Object.keys(criteria).length;
                const percentage = (score / total) * 100;

                // Update progress bar
                strengthBar.style.width = percentage + '%';

                // Update strength text and color
                if (score === total) {
                    strengthBar.className = 'progress-bar bg-success';
                    strengthText.textContent = 'Kuat - Password memenuhi semua kriteria';
                    strengthText.className = 'form-text text-success';
                } else if (score >= 5) {
                    strengthBar.className = 'progress-bar bg-warning';
                    strengthText.textContent = 'Sedang - Password perlu diperkuat';
                    strengthText.className = 'form-text text-warning';
                } else {
                    strengthBar.className = 'progress-bar bg-danger';
                    strengthText.textContent = 'Lemah - Password tidak aman';
                    strengthText.className = 'form-text text-danger';
                }

                validateForm();
            }

            function checkPasswordMatch() {
                const password = passwordInput.value;
                const rePassword = rePasswordInput.value;
                const matchElement = document.getElementById('password-match');

                if (rePassword === '') {
                    matchElement.textContent = '';
                    matchElement.className = 'form-text';
                } else if (password === rePassword) {
                    matchElement.textContent = '✓ Password cocok';
                    matchElement.className = 'form-text text-success';
                } else {
                    matchElement.textContent = '✗ Password tidak cocok';
                    matchElement.className = 'form-text text-danger';
                }

                validateForm();
            }

            function validateForm() {
                const password = passwordInput.value;
                const rePassword = rePasswordInput.value;
                const currentPassword = document.getElementById('current_password').value;

                // Check if all criteria are met and passwords match
                const allCriteriaMet = Array.from(document.querySelectorAll('.password-criteria i'))
                    .every(icon => icon.className.includes('fa-check'));

                const passwordsMatch = password === rePassword && password !== '';
                const currentPasswordFilled = currentPassword !== '';

                submitBtn.disabled = !(allCriteriaMet && passwordsMatch && currentPasswordFilled);
            }

            // Also validate when current password changes
            document.getElementById('current_password').addEventListener('input', validateForm);
        });
    </script>
</body>

</html>