<!DOCTYPE html>
<html lang="en">

<head>
    <title>Kementerian Pertahanan | Reset Password</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url(); ?>assets/login/images/Logo_kemhan.png" />
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/login/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css"
        href="<?= base_url(); ?>assets/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url(); ?>assets/login/css/kemhan-auth-theme.css">
    <style>
        /* Menambahkan style untuk ikon mata di dalam input */
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
            /* Memastikan ikon di atas input */
        }
    </style>
</head>

<body>
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form class="login100-form" action="<?= base_url('Login/proses_reset_password') ?>" method="POST">

                    <span class="login100-form-title p-b-20">
                        Reset Password Anda
                    </span>
                    <p class="sub-title text-center p-b-20">
                        Masukkan password baru Anda di bawah ini.
                    </p>

                    <!-- Area untuk notifikasi error -->
                    <?php if ($this->session->flashdata('error_form')): ?>
                        <div class="alert alert-danger">
                            <?= $this->session->flashdata('error_form'); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Input tersembunyi untuk membawa token -->
                    <input type="hidden" name="token" value="<?= $token; ?>">

                    <div class="wrap-input100">
                        <input class="input100" type="password" name="password" id="password"
                            placeholder="Password Baru" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <span class="toggle-password">
                            <i class="fa fa-eye" id="togglePassword" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="wrap-input100">
                        <input class="input100" type="password" name="confirm_password" id="confirm_password"
                            placeholder="Konfirmasi Password Baru" required>
                        <span class="symbol-input100">
                            <i class="fa fa-lock" aria-hidden="true"></i>
                        </span>
                        <span class="toggle-password">
                            <i class="fa fa-eye" id="toggleConfirmPassword" aria-hidden="true"></i>
                        </span>
                    </div>

                    <div class="container-login100-form-btn">
                        <button class="login100-form-btn">
                            Reset Password
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="<?= base_url(); ?>assets/login/vendor/jquery/jquery-3.2.1.min.js"></script>
    <script src="<?= base_url(); ?>assets/login/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function () {
            // Logika untuk ikon mata pada input password baru
            $("#togglePassword").click(function () {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $("#password");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });

            // Logika untuk ikon mata pada input konfirmasi password
            $("#toggleConfirmPassword").click(function () {
                $(this).toggleClass("fa-eye fa-eye-slash");
                var input = $("#confirm_password");
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        });
    </script>
</body>

</html>