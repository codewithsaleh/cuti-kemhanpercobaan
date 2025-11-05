<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SI Cuti Pegawai | Kemhan</title>

<!--===============================================================================================-->
<!-- [MODIFIKASI] Menggunakan logo Kemhan sebagai favicon -->
<link rel="icon" type="image/png" href="<?= base_url();?>assets/login/images/Logo_kemhan.png" />

<!-- [MODIFIKASI] Menggunakan font Poppins agar seragam dengan login -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<!-- Font Awesome -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/plugins/fontawesome-free/css/all.min.css">
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/dist/css/adminlte.min.css">
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
<!-- DataTables -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

<!-- [MODIFIKASI] Memuat file tema baru kita -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/dist/css/kemhan-admin-theme.css">

<!-- Sweetalert -->
<script src="<?= base_url(); ?>node_modules/sweetalert/dist/sweetalert.min.js"></script>

<!--
 * =================================================================
 * [PERBAIKAN FINAL] CSS RESPONSIVE GLOBAL UNTUK ADMIN
 * Ditempatkan di sini untuk memastikan prioritas tertinggi dan bypass cache.
 * =================================================================
-->
<style>
    /*
    * -----------------------------------------------------------------
    * Tampilan Tablet & HP (Layar di bawah 992px)
    * -----------------------------------------------------------------
    */
    @media (max-width: 991.98px) {
        /*
        * [PERBAIKAN UTAMA]
        * Memaksa konten memenuhi layar saat sidebar utama tertutup di mobile/tablet.
        * :not(.sidebar-open) berarti "saat sidebar TIDAK sedang terbuka".
        */
        body:not(.sidebar-open) .content-wrapper,
        body:not(.sidebar-open) .main-header,
        body:not(.sidebar-open) .main-footer {
            margin-left: 0 !important;
        }

        /*
        * [PERBAIKAN TABLET]
        * Memaksa sidebar untuk benar-benar sembunyi saat ditutup,
        * bukan hanya menjadi ikon kecil (perilaku default di tablet).
        */
        body.sidebar-collapse .main-sidebar {
            left: -300px !important; /* Dorong keluar layar */
        }
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-header,
        body.sidebar-collapse .main-footer {
            margin-left: 0 !important; /* Pastikan konten memenuhi layar */
        }
    }
</style>
