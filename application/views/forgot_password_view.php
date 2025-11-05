<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kementerian Pertahanan | Lupa Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url();?>assets/login/images/Logo_kemhan.png" />
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/css/kemhan-auth-theme.css">
    
    <!-- [PERBAIKAN] Menambahkan gaya untuk merapikan notifikasi -->
    <style>
        .alert {
            /* [FIX] Memaksa teks panjang (seperti token) untuk pindah baris */
            word-wrap: break-word;
            overflow-wrap: break-word;
            border-radius: 10px; /* Menambahkan sudut membulat */
            font-size: 5px;
        }
        .alert.alert-success {
            background-color: #e6f7ff; /* Latar belakang biru muda */
            border-color: #91d5ff;
            color: #0050b3;
        }
        .alert.alert-success a {
            font-weight: bold;
            color: #00b315ff; /* Warna link disesuaikan */
            text-decoration: underline;
        }
        .alert.alert-success a:hover {
            color: #003a8c;
        }
        .alert.alert-danger {
            background-color: #fff1f0; /* Latar belakang merah muda */
            border-color: #ffa39e;
            color: #cf1322;
        }
    </style>
</head>
<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form" action="<?= base_url('Login/proses_lupa_password') ?>" method="POST">
                    
                    <span class="login100-form-title p-b-20">
                        Lupa Password
                    </span>
                    <p class="sub-title text-center p-b-20">
                        Masukkan email Anda yang terdaftar untuk menerima instruksi reset password.
                    </p>

                    <!-- Area Notifikasi -->
                    <?php if($this->session->flashdata('success_request')): ?>
                        <div class="alert alert-success">
                            <?= $this->session->flashdata('success_request'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if($this->session->flashdata('error_request')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error_request'); ?>
                        </div>
                    <?php endif; ?>
                     <?php if($this->session->flashdata('error_reset')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error_reset'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="wrap-input100">
                        <input class="input100" type="email" name="email" placeholder="Email" required>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Kirim Instruksi Reset
                        </button>
                    </div>

                    <div class="text-center p-t-20">
                        <a class="txt2" href="<?= base_url('Login/index') ?>">
                            Kembali ke Halaman Login
                            <i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="<?= base_url();?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

