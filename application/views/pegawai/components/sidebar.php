<aside class="main-sidebar sidebar-dark-primary elevation-4" id="sidebar-pegawai">
    <!-- Brand Logo -->
    <a href="<?= base_url('Dashboard/dashboard_pegawai'); ?>" class="sidebar-header-link">
        <div class="sidebar-header">
            <img src="<?= base_url(); ?>assets/login/images/Logo_kemhan.png" alt="Logo Kemhan" class="brand-logo">
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
                    <a href="<?= base_url(); ?>Dashboard/dashboard_pegawai" class="nav-link <?php if ($this->uri->segment(1) == 'Dashboard') {
                          echo 'active';
                      } ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Data Cuti - Riwayat Cuti -->
                <!-- Data Cuti -->
                <li class="nav-item">
                    <a href="<?= site_url('Cuti/view_pegawai') ?>" class="nav-link <?php
                      $current_url = current_url();
                      $base_url = base_url();

                      // List semua URL yang termasuk Data Cuti
                      $data_cuti_urls = array(
                          $base_url . 'Cuti/view_pegawai',
                          $base_url . 'Cuti/view_pegawai_menunggu',
                          $base_url . 'Cuti/view_pegawai_reject',
                          $base_url . 'Cuti/view_pegawai_acc'
                      );

                      echo in_array($current_url, $data_cuti_urls) ? 'active' : '';
                      ?>">
                        <i class="nav-icon fas fa-calendar-alt"></i>
                        <p>Data Cuti</p>
                    </a>
                </li>

                <!-- Permohonan Cuti - Form Pengajuan -->
                <li class="nav-item">
                    <a href="<?= site_url('Cuti/add_cuti_pegawai'); ?>" class="nav-link <?php if ($this->uri->segment(1) == 'Cuti' && $this->uri->segment(2) == 'add_cuti_pegawai') {
                          echo 'active';
                      } ?>">
                        <i class="nav-icon fas fa-edit"></i>
                        <p>Permohonan Cuti</p>
                    </a>
                </li>

                <!-- Pesan -->
                <li class="nav-item">
                    <a href="<?= base_url(); ?>Pesan/view_pegawai" class="nav-link <?php if ($this->uri->segment(1) == 'Pesan') {
                          echo 'active';
                      } ?>">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Pesan</p>
                    </a>
                </li>

                <!-- Settings -->
                <li class="nav-item">
                    <a href="<?= base_url(); ?>Settings/view_pegawai" class="nav-link <?php if ($this->uri->segment(1) == 'Settings') {
                          echo 'active';
                      } ?>">
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