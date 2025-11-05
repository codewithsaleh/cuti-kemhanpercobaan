<aside class="main-sidebar sidebar-dark-primary elevation-4" id="sidebar-pegawai">
    <!-- Brand Logo -->
    <a href="<?= base_url('Dashboard/dashboard_pegawai'); ?>" class="sidebar-header-link">
        <div class="sidebar-header">
            <img src="<?= base_url();?>assets/login/images/Logo_kemhan.png" alt="Logo Kemhan" class="brand-logo">
            <div class="brand-info">
                <span class="brand-title">SI Cuti Pegawai</span>
                <span class="brand-subtitle">Kementerian Pertahanan</span>
            </div>
        </div>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="<?= base_url();?>Dashboard/dashboard_pegawai" class="nav-link <?php if($this->uri->segment(1) == 'Dashboard'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('Cuti/view_pegawai') ?>" class="nav-link <?php if($this->uri->segment(1) == 'Cuti'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Data Cuti</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url();?>Form_Cuti/view_pegawai" class="nav-link <?php if($this->uri->segment(1) == 'Form_Cuti'){ echo 'active';} ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Permohonan Cuti</p>
                    </a>
                </li>

                <!-- [MENU BARU] Menambahkan menu Pesan untuk Pegawai -->
                <li class="nav-item">
                    <a href="<?= base_url('Pesan/view_pegawai') ?>" class="nav-link">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Pesan</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="<?= base_url();?>Settings/view_pegawai" class="nav-link <?php if($this->uri->segment(1) == 'Settings'){ echo 'active';} ?>">
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

