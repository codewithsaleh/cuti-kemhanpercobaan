<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SI Cuti Pegawai | Kemhan</title>

<!--===============================================================================================-->
<!-- Favicon -->
<link rel="icon" type="image/png" href="<?= base_url();?>assets/login/images/Logo_kemhan.png" />

<!-- Google Font -->
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

<!-- Tema Umum Admin (kemhan-admin-theme.css) -->
<link rel="stylesheet" href="<?= base_url();?>assets/admin_lte/dist/css/kemhan-admin-theme.css">

<!-- [PENTING] Tema Khusus untuk Halaman Pegawai -->
<link rel="stylesheet" href="<?= base_url();?>assets/css/pegawai-theme.css">

<!-- Sweetalert -->
<script src="<?= base_url(); ?>node_modules/sweetalert/dist/sweetalert.min.js"></script>

<!--
 * =================================================================
 * [PERBAIKAN FINAL] CSS RESPONSIVE GLOBAL UNTUK PEGAWAI
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
        * Memaksa konten memenuhi layar saat sidebar utama tertutup.
        * :not(.sidebar-open) berarti "saat sidebar tidak sedang terbuka".
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
            left: -300px !important;
        }
        body.sidebar-collapse .content-wrapper,
        body.sidebar-collapse .main-header,
        body.sidebar-collapse .main-footer {
            margin-left: 0 !important;
        }

        /* Perkecil ukuran font judul utama */
        .content-header h1 {
            font-size: 1.5rem;
        }
    }

    /*
    * -----------------------------------------------------------------
    * Tampilan Handphone (Layar di bawah 768px)
    * -----------------------------------------------------------------
    */
    @media (max-width: 767.98px) {
        /* Judul halaman rata tengah */
        .content-header .col-sm-6 {
            text-align: center;
            width: 100%;
        }
        .content-header .breadcrumb {
            float: none !important;
            justify-content: center;
            margin-top: 10px;
        }

        /* Stat box dashboard menjadi 2 kolom */
        .col-lg-3.col-6 {
            flex: 0 0 50%;
            max-width: 50%;
        }

        /* Kontrol DataTables full width */
        .dataTables_filter,
        .dataTables_length {
            text-align: left !important;
            width: 100%;
        }
        .dt-buttons {
            float: none !important;
            text-align: left !important;
            margin-bottom: 10px;
        }
        
        /* Header kartu (card-header) */
        .card-header {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .card-header .card-tools {
            padding-top: 10px;
        }
    }

    /*
    * -----------------------------------------------------------------
    * Tampilan Handphone Sangat Kecil (Layar di bawah 576px)
    * -----------------------------------------------------------------
    */
    @media (max-width: 575.98px) {
        /* Stat box dashboard menjadi 1 kolom */
        .col-lg-3.col-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }

        /* Perkecil padding konten */
        .content {
            padding: 0.5rem !important;
        }
    }
</style>

</head>

