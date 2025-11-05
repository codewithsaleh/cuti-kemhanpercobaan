<nav class="main-header navbar navbar-expand navbar-dark" style="background-color: #355b7fff !important; border-bottom: 1px solid #355b7fff !important;">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="<?= base_url();?>Settings/view_pegawai" class="dropdown-item dropdown-footer">Lengkapi Data / Settings</a>
                <a href="<?= base_url();?>Login/log_out" class="dropdown-item dropdown-footer">Logout</a>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <!-- [MODIFIKASI] Tombol Control Sidebar Dihapus -->
    </ul>
</nav>

