<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("admin/components/header.php") ?>

    <style>
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

        .password-criteria li {
            font-size: 12px;
            margin-bottom: 2px;
        }

        .password-criteria i {
            margin-right: 5px;
            width: 15px;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <!-- Notifications -->
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

    <div class="wrapper">
        <?php $this->load->view("admin/components/navbar.php") ?>
        <?php $this->load->view("admin/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">
                                <i class="fas fa-shield-alt text-primary"></i>
                                Pengaturan Keamanan Sistem
                            </h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a
                                        href="<?= base_url('Dashboard/dashboard_admin') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Settings</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
    <!-- Kolom Data Diri -->
    <div class="col-md-7">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fa fa-user-circle me-1"></i> Data Diri Admin
            </h3>
        </div>
        <div class="card-body">
            <form action="<?= base_url(); ?>Settings/lengkapi_data_admin" method="POST">
                <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">
                <input type="hidden" name="id_user_detail" value="<?= $user['id_user_detail'] ?>">

                <!-- Username -->
                <div class="form-group">
                    <label for="username">Username (NIP/NRP)</label>
                    <input type="text" class="form-control" name="username" value="<?= $user['username'] ?>" readonly>
                    <small class="text-muted">
                        <i class="fas fa-info-circle"></i> Username tidak dapat diubah karena digunakan untuk login
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?= $user['email'] ?>" required>
                            <small class="text-muted">Email digunakan untuk notifikasi dan reset password</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nip">NIP / NRP</label>
                            <input type="text" class="form-control" name="nip" value="<?= $user['nip'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pangkat">Pangkat / Golongan</label>
                            <select class="form-control" name="pangkat">
                                <option value="" disabled>Pilih Pangkat / Golongan</option>
                                <optgroup label="Golongan IV (Pembina)">
                                    <option value="Pembina Utama (IV/e)" <?= $user['pangkat'] == 'Pembina Utama (IV/e)' ? 'selected' : '' ?>>Pembina Utama (IV/e)</option>
                                    <option value="Pembina Utama Madya (IV/d)" <?= $user['pangkat'] == 'Pembina Utama Madya (IV/d)' ? 'selected' : '' ?>>Pembina Utama Madya (IV/d)</option>
                                    <option value="Pembina Utama Muda (IV/c)" <?= $user['pangkat'] == 'Pembina Utama Muda (IV/c)' ? 'selected' : '' ?>>Pembina Utama Muda (IV/c)</option>
                                    <option value="Pembina Tingkat I (IV/b)" <?= $user['pangkat'] == 'Pembina Tingkat I (IV/b)' ? 'selected' : '' ?>>Pembina Tingkat I (IV/b)</option>
                                    <option value="Pembina (IV/a)" <?= $user['pangkat'] == 'Pembina (IV/a)' ? 'selected' : '' ?>>Pembina (IV/a)</option>
                                </optgroup>
                                <optgroup label="Golongan III (Penata)">
                                    <option value="Penata Tingkat I (III/d)" <?= $user['pangkat'] == 'Penata Tingkat I (III/d)' ? 'selected' : '' ?>>Penata Tingkat I (III/d)</option>
                                    <option value="Penata (III/c)" <?= $user['pangkat'] == 'Penata (III/c)' ? 'selected' : '' ?>>Penata (III/c)</option>
                                    <option value="Penata Muda Tingkat I (III/b)" <?= $user['pangkat'] == 'Penata Muda Tingkat I (III/b)' ? 'selected' : '' ?>>Penata Muda Tingkat I (III/b)</option>
                                    <option value="Penata Muda (III/a)" <?= $user['pangkat'] == 'Penata Muda (III/a)' ? 'selected' : '' ?>>Penata Muda (III/a)</option>
                                </optgroup>
                                <optgroup label="TNI - Perwira Tinggi">
                                    <option value="Jenderal" <?= $user['pangkat'] == 'Jenderal' ? 'selected' : '' ?>>Jenderal</option>
                                    <option value="Letnan Jenderal" <?= $user['pangkat'] == 'Letnan Jenderal' ? 'selected' : '' ?>>Letnan Jenderal</option>
                                    <option value="Mayor Jenderal" <?= $user['pangkat'] == 'Mayor Jenderal' ? 'selected' : '' ?>>Mayor Jenderal</option>
                                    <option value="Brigadir Jenderal" <?= $user['pangkat'] == 'Brigadir Jenderal' ? 'selected' : '' ?>>Brigadir Jenderal</option>
                                </optgroup>
                                <option value="Lainnya" <?= $user['pangkat'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jabatan">Jabatan <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="jabatan" value="<?= $user['jabatan'] ?>" required placeholder="Masukkan Jabatan">
                            <small class="text-muted">Contoh: Staf Administrasi, Analis Kebijakan, dll.</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
    <label for="id_jenis_kelamin">Jenis Kelamin <span class="text-danger">*</span></label>
    <select class="form-control" name="id_jenis_kelamin" required>
        <option value="">Pilih Jenis Kelamin</option>
        <?php foreach ($jenis_kelamin as $jk): ?>
                                    <option value="<?= $jk["id_jenis_kelamin"] ?>" <?= ($jk["id_jenis_kelamin"] == $user['id_jenis_kelamin']) ? 'selected' : '' ?>>
                                        <?= $jk["jenis_kelamin"] ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- No. Telepon dan Alamat dalam satu kolom penuh -->
                <div class="form-group">
                    <label for="no_telp">No. Telepon / HP</label>
                    <input type="text" class="form-control" name="no_telp" value="<?= $user['no_telp'] ?>">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <textarea class="form-control" name="alamat" rows="2"><?= $user['alamat'] ?></textarea>
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
        <div class="card border-warning">
            <div class="card-header bg-warning text-white">
                <h3 class="card-title">
                    <i class="fas fa-key mr-2"></i>
                    Ganti Password Admin
                </h3>
            </div>
            <div class="card-body">
                <div class="alert alert-secondary">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Keamanan Tinggi:</strong> Sistem Kemhan memerlukan password yang kuat!
                </div>

                <form action="<?= base_url('Settings/settings_account_admin') ?>" method="POST" id="passwordForm">
                    <!-- Username -->
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" value="<?= $this->session->userdata('username') ?>" readonly>
                        <small class="text-muted">Username tidak dapat diubah</small>
                    </div>

                    <!-- Password Saat Ini -->
                    <div class="form-group">
                        <label for="current_password">Password Saat Ini <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="current_password" name="current_password" required autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly')" style="background-color: white;">
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" data-target="current_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <small class="text-muted">Masukkan password saat ini untuk verifikasi</small>
                    </div>

                    <!-- Password Baru -->
                    <div class="form-group">
                        <label>Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="password" id="password" placeholder="Masukkan password baru" required>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>

                        <!-- Status Kekuatan Password -->
                        <div class="password-strength mt-2">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar" id="password-strength-bar" role="progressbar" style="width: 0%"></div>
                            </div>
                            <small id="password-strength-text" class="form-text"></small>
                        </div>

                        <!-- Kriteria Password -->
                        <div class="password-criteria mt-2">
                            <small class="text-muted">Kriteria password:</small>
                            <ul class="list-unstyled mb-0">
                                <li id="criteria-length"><i class="fas fa-times text-danger"></i> Minimal 8 karakter</li>
                                <li id="criteria-uppercase"><i class="fas fa-times text-danger"></i> Mengandung huruf besar (A-Z)</li>
                                <li id="criteria-lowercase"><i class="fas fa-times text-danger"></i> Mengandung huruf kecil (a-z)</li>
                                <li id="criteria-number"><i class="fas fa-times text-danger"></i> Mengandung angka (0-9)</li>
                                <li id="criteria-special"><i class="fas fa-times text-danger"></i> Mengandung karakter khusus (!@#$%)</li>
                                <li id="criteria-username"><i class="fas fa-times text-danger"></i> Tidak sama dengan username</li>
                                <li id="criteria-year"><i class="fas fa-times text-danger"></i> Tidak mengandung tahun berjalan</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Konfirmasi Password Baru -->
                    <div class="form-group">
                        <label>Konfirmasi Password Baru <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" name="re_password" id="re_password" placeholder="Ulangi password baru" required>
                            <div class="input-group-append">
                                <span class="input-group-text toggle-password" data-target="re_password">
                                    <i class="fas fa-eye"></i>
                                </span>
                            </div>
                        </div>
                        <small id="password-match" class="form-text"></small>
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn btn-warning" id="submitBtn" disabled>
                            <i class="fas fa-shield-alt"></i> Ubah Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Baris Kedua: Reset Cuti -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">
                    <i class="fas fa-calendar-times mr-2"></i>
                    Pengaturan Reset Cuti Tahunan
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Pengaturan Mode Reset -->
                    <div class="col-md-8">
                        <p class="text-muted">
                            Mode saat ini: <strong><?= ucfirst($reset_mode ?? 'manual') ?></strong> |
                            Reset Terakhir:
                            <strong><?= !empty($last_reset_year) ? "Tahun {$last_reset_year}" : "Belum pernah direset" ?></strong>
                        </p>

                        <form action="<?= base_url('Settings/update_reset_mode') ?>" method="POST" id="resetModeForm">
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reset_mode" id="manual" value="manual" <?= (($reset_mode ?? 'manual') == 'manual') ? 'checked' : '' ?>>
                                    <label class="form-check-label font-weight-bold text-primary" for="manual">
                                        Reset Manual
                                    </label>
                                    <small class="form-text text-muted">
                                        Admin harus menekan tombol reset secara manual setiap awal tahun.
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="reset_mode" id="otomatis" value="otomatis" <?= (($reset_mode ?? 'manual') == 'otomatis') ? 'checked' : '' ?>>
                                    <label class="form-check-label font-weight-bold text-success" for="otomatis">
                                        Reset Otomatis
                                    </label>
                                    <small class="form-text text-muted">
                                        Sistem akan otomatis mereset jatah cuti pada tanggal 1 Januari setiap tahun saat admin login.
                                    </small>
                                </div>
                            </div>

                            <div class="text-left">
                                <button type="submit" class="btn btn-primary" id="saveResetMode">
                                    <i class="fas fa-save"></i> Simpan Pengaturan
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Aksi Reset Manual -->
                    <div class="col-md-4">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark">
                                <h4 class="card-title mb-0">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    Aksi Reset Manual
                                </h4>
                            </div>
                            <div class="card-body">
                                <p class="text-muted small">
                                    Gunakan tombol ini jika Anda memilih mode "Reset Manual".
                                    Tindakan ini akan mereset jatah cuti semua pegawai menjadi 12 hari.
                                </p>

                                <div class="text-center">
                                    <button class="btn btn-danger btn-sm" onclick="confirmReset()">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Reset Jatah Cuti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
                </div>
            </section>
        </div>
    </div>

    <?php $this->load->view("admin/components/js.php") ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const passwordInput = document.getElementById('password');
            const rePasswordInput = document.getElementById('re_password');
            const currentPasswordInput = document.getElementById('current_password');
            const strengthBar = document.getElementById('password-strength-bar');
            const strengthText = document.getElementById('password-strength-text');
            const submitBtn = document.getElementById('submitBtn');
            const username = '<?= $this->session->userdata("username") ?>';

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

            // [FIX] Clear field current password immediately
            currentPasswordInput.value = '';

            // [FIX] Force clear after a short delay (untuk handle browser yang bandel)
            setTimeout(function () {
                currentPasswordInput.value = '';
                currentPasswordInput.setAttribute('readonly', 'readonly');
            }, 50);

            // Toggle Password Functionality
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
            currentPasswordInput.addEventListener('input', validateForm);

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
                const currentPassword = currentPasswordInput.value;

                // Check if all criteria are met and passwords match
                const allCriteriaMet = Array.from(document.querySelectorAll('.password-criteria i'))
                    .every(icon => icon.className.includes('fa-check'));

                const passwordsMatch = password === rePassword && password !== '';
                const currentPasswordFilled = currentPassword !== '';

                submitBtn.disabled = !(allCriteriaMet && passwordsMatch && currentPasswordFilled);
            }

            // [FIX] Handle ketika user klik field current password
            currentPasswordInput.addEventListener('focus', function () {
                this.removeAttribute('readonly');
                // Clear value jika masih ada isian otomatis
                if (this.value === 'password') {
                    this.value = '';
                }
            });

            // [FIX] Initial validation
            validateForm();
        });
    </script>


    <!-- Reset Cuti -->
    <script>
        function confirmReset() {
            swal({
                title: "Konfirmasi Reset Cuti Tahunan",
                text: "Tindakan ini akan mereset jatah cuti semua pegawai menjadi 12 hari dan menghapus riwayat cuti yang diterima. Apakah Anda yakin?",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Batal",
                        value: null,
                        visible: true,
                        className: "btn btn-secondary",
                        closeModal: true,
                    },
                    confirm: {
                        text: "Ya, Reset Sekarang!",
                        value: true,
                        visible: true,
                        className: "btn btn-danger",
                        closeModal: false
                    }
                }
            }).then((willReset) => {
                if (willReset) {
                    // Show loading
                    swal({
                        title: "Memproses Reset...",
                        text: "Mohon tunggu, sistem sedang mereset jatah cuti.",
                        icon: "info",
                        buttons: false,
                        closeOnClickOutside: false,
                        closeOnEsc: false
                    });

                    // Redirect to reset process
                    window.location.href = "<?= base_url('Settings/proses_reset_cuti') ?>";
                }
            });
        }

        // Reset mode form validation
        document.getElementById('resetModeForm').addEventListener('submit', function (e) {
            const selectedMode = document.querySelector('input[name="reset_mode"]:checked');

            if (!selectedMode) {
                e.preventDefault();
                swal('Error!', 'Pilih mode reset cuti terlebih dahulu!', 'error');
                return false;
            }

            // Confirm before saving
            const mode = selectedMode.value;
            const modeText = (mode === 'otomatis') ? 'OTOMATIS' : 'MANUAL';

            e.preventDefault();
            swal({
                title: "Konfirmasi Perubahan",
                text: `Anda akan mengatur mode reset cuti menjadi ${modeText}. Lanjutkan?`,
                icon: "info",
                buttons: {
                    cancel: "Batal",
                    confirm: {
                        text: "Ya, Simpan",
                        value: true
                    }
                }
            }).then((confirmed) => {
                if (confirmed) {
                    // Submit form
                    document.getElementById('resetModeForm').submit();
                }
            });
        });

    </script>
</body>

</html>