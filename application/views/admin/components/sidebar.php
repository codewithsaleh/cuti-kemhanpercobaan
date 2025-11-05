<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- [BARU] Header Sidebar Kustom -->
    <div class="sidebar-header">
        <div class="d-flex align-items-center">
            <img src="<?= base_url();?>assets/login/images/Logo_kemhan.png" alt="Logo Kemhan" class="brand-logo">
            <div class="brand-info">
                <span class="brand-title">SI Cuti Pegawai</span>
                <span class="brand-subtitle">Kementerian Pertahanan</span>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Menu Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Dashboard/dashboard_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Dashboard' || $this->uri->segment(1) == ''){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- [MODIFIKASI] Mengganti nama menu menjadi Pengguna -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Pegawai/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Pegawai'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Pengguna</p>
                    </a>
                </li>

                <!-- Menu Cuti -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Cuti/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Cuti'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Cuti</p>
                    </a>
                </li>

                <!-- [FITUR BARU] Menu Laporan -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Laporan/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Laporan'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan</p>
                    </a>
                </li>
                
                <!-- [FITUR BARU] Menu Kalender Tim -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Kalender/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Kalender'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Kalender Tim</p>
                    </a>
                </li>

                <!-- [FITUR BARU] Menu Pesan/Chat -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Pesan/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Pesan'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Pesan</p>
                    </a>
                </li>

                <!-- Menu Settings -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Settings/view_admin"
                        class="nav-link <?php if($this->uri->segment(1) == 'Settings'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>Settings</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
