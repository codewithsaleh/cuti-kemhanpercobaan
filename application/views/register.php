<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kementerian Pertahanan | Registrasi</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url();?>assets/login/images/Logo_kemhan.png" />
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/css/kemhan-auth-theme.css">
    <script src="<?= base_url() ?>node_modules/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <?php if ($this->session->flashdata('password_err')){ echo "<script>swal('Error Password!', 'Ketik Ulang Password!', 'error');</script>"; } ?>

    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form" action="<?= base_url();?>Register/proses" method="POST">
                    
                    <div class="login100-pic">
                        <img src="<?= base_url();?>assets/login/images/Logo_kemhan.png" alt="Logo Kementerian Pertahanan">
                    </div>

                    <span class="login100-form-title">
                        Buat Akun Baru
                    </span>
                     <p class="sub-title">
                        Sistem Informasi Cuti Pegawai
                    </p>

                    <div class="wrap-input100">
                        <input class="input100" type="text" name="username" placeholder="Username" required>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="email" name="email" placeholder="Email" required>
                        <span class="symbol-input100">
                            <i class="fa fa-envelope" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="password" name="password" placeholder="Password" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                     <div class="wrap-input100">
                        <input class="input100" type="password" name="re_password" placeholder="Ulangi Password" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Daftar
                        </button>
                    </div>

                    <div class="text-center">
                        <a class="txt2" href="<?=base_url();?>Login/index">
                            Sudah punya akun? Login di sini
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="<?= base_url();?>assets/login/vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url();?>assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>

