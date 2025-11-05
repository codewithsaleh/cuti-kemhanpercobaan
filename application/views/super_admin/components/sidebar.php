<aside class="main-sidebar sidebar-dark-primary elevation-4">

    <!-- [MODIFIKASI] Menggunakan Header Sidebar Kustom dari Admin -->
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
         <!-- [BARU] Menampilkan nama Atasan yang login -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= base_url();?>assets/admin_lte/dist/img/account.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block text-white"><?=$this->session->userdata('nama_lengkap');?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- [MODIFIKASI] Menu Dashboard Atasan -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Atasan/dashboard"
                        class="nav-link <?php if($this->uri->segment(2) == 'dashboard' || $this->uri->segment(1) == 'Atasan' && $this->uri->segment(2) == ''){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-desktop"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- [MENU BARU] Manajemen Cuti Tim (Fitur Utama) -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Atasan/manajemen_cuti"
                        class="nav-link <?php if($this->uri->segment(2) == 'manajemen_cuti'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-envelope-open-text"></i>
                        <p>Manajemen Tim</p>
                    </a>
                </li>

                <!-- [MENU BARU] Data Tim (Read-Only) -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Atasan/data_tim"
                        class="nav-link <?php if($this->uri->segment(2) == 'data_tim'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Tim</p>
                    </a>
                </li>

                <!-- [MENU BARU] Laporan Tim -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Atasan/laporan"
                        class="nav-link <?php if($this->uri->segment(2) == 'laporan'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>Laporan Tim</p>
                    </a>
                </li>

                <!-- [MODIFIKASI] Menu Settings Atasan -->
                <li class="nav-item">
                    <a href="<?= base_url();?>Settings/view_super_admin"
                        class="nav-link <?php if($this->uri->segment(2) == 'settings'){ echo 'active';} ?>">
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
