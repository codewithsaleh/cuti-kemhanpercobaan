<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kementerian Pertahanan | Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url();?>assets/login/images/Logo_kemhan.png" />
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url();?>assets/login/css/kemhan-auth-theme.css">
    <script src="<?= base_url() ?>node_modules/sweetalert/dist/sweetalert.min.js"></script>
    <style>
        /* [BARU] Menambahkan style untuk ikon mata di dalam input */
        .wrap-input100 {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666666;
            z-index: 2;
        }
    </style>
</head>
<body>
    <?php if($this->session->flashdata('loggin_err_pass')){ echo "<script>swal('Error!', 'Password yang Anda masukan salah!', 'error');</script>"; } ?>
    <?php if($this->session->flashdata('loggin_err_no_user')){ echo "<script>swal('Error!', 'Anda belum terdaftar!', 'error');</script>"; } ?>
    <?php if($this->session->flashdata('success_reset_final')){ echo "<script>swal('Success!', 'Password Anda telah berhasil direset. Silakan login.', 'success');</script>"; } ?>


    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form" action="<?= base_url();?>Login/proses" method="POST">
                    
                    <div class="login100-pic">
                        <img src="<?= base_url();?>assets/login/images/Logo_kemhan.png" alt="Logo Kementerian Pertahanan">
                    </div>

                    <span class="login100-form-title">
                        Sistem Informasi Cuti Pegawai
                    </span>
                    <p class="sub-title">
                        Kementerian Pertahanan
                    </p>

                    <div class="wrap-input100">
                        <input class="input100" type="text" name="username" placeholder="Username" required>
                        <span class="symbol-input100">
                            <i class="fa fa-user" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="password" id="password-field" name="password" placeholder="Password" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <!-- [BARU] Ikon mata untuk toggle password -->
                        <span class="toggle-password">
                            <i class="fa fa-eye" id="toggle-password" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Login
                        </button>
                    </div>

                    
    
    <script src="<?= base_url();?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url();?>assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // [BARU] Skrip untuk fungsionalitas ikon mata
        $(document).ready(function(){
            $("#toggle-password").click(function(){
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $("#password-field");
                if (input.attr("type") == "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
</body>
</html>

