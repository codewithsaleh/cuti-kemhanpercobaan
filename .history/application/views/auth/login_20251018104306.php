<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Cuti Kemhan</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h4>Sistem Cuti Kemhan</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo site_url('auth/login'); ?>" method="post">
                        <div class="form-group">
                            <label for="username">NIP/NRP atau Username</label>
                            <input type="text" class="form-control" id="username" name="username" 
                                   placeholder="Masukkan NIP/NRP atau Username" required>
                            <small class="text-muted">Pegawai gunakan NIP/NRP, Admin gunakan username</small>
                        </div>

                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" 
                                   placeholder="Masukkan Password" required>
                            <small class="text-muted">Password default pegawai baru: kemhan2025</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <small class="text-muted">© 2024 Kementerian Pertahanan Republik Indonesia</small>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
</body>
</html>