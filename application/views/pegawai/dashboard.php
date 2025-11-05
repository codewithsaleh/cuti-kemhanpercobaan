<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>
    <style>
        .card-header-info {
            background-color: #007bff;
            /* Blue background */
            color: white;
            /* White text */
            font-weight: 600;
        }

        .card-header-info .card-title {
            color: white;
            /* Ensure title text is white */
            font-weight: 600;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <?php if ($this->session->flashdata('input')) { ?>
        <script>
            swal({
                title: "Success!",
                text: "Data Berhasil Ditambahkan!",
                icon: "success"
            });
        </script>
    <?php } ?>

    <?php if ($this->session->flashdata('eror')) { ?>
        <script>
            swal({
                title: "Erorr!",
                text: "Data Gagal Ditambahkan!",
                icon: "error"
            });
        </script>
    <?php } ?>

    <div class="wrapper">

        <!-- Navbar -->
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Dashboard</h1>

                            <p class="text-muted mt-2">
                                <?= $sapaan ?>, <strong><?= htmlspecialchars($pegawai['nama_lengkap']) ?></strong>
                            </p>

                            <!-- [BARU] Tampilkan informasi atasan -->
                            <?php if (!empty($pegawai['id_atasan']) && !empty($pegawai['nama_atasan'])): ?>
                                <div class="mt-1">
                                    <small class="text-info">
                                        <i class="fas fa-user-tie"></i>
                                        Pegawai dari: <strong><?= htmlspecialchars($pegawai['nama_atasan']) ?></strong>
                                        <?php if (!empty($pegawai['jabatan_atasan'])): ?>
                                            - <?= htmlspecialchars($pegawai['jabatan_atasan']) ?>
                                        <?php endif; ?>
                                    </small>
                                </div>
                            <?php else: ?>
                                <div class="mt-1">
                                    <small class="text-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Belum memiliki atasan yang ditetapkan
                                    </small>
                                </div>
                            <?php endif; ?>

                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div>
            </div>
    

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-gradient-purple">
                                <div class="inner">
                                    <h3><?= $cuti['total_cuti'] ?></h3>
                                    <p>Total Pengajuan</p>
                                </div>
                                <a href="<?= base_url('Cuti/view_pegawai') ?>" class="small-box-footer">Detail <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-gradient-blue">
                                <div class="inner">
                                    <h3><?= $cuti_acc['total_cuti'] ?></h3>
                                    <p>Diterima</p>
                                </div>
                                <a href="<?= base_url('Cuti/view_pegawai_acc') ?>" class="small-box-footer">Detail
                                    <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-gradient-orange">
                                <div class="inner">
                                    <h3><?= $cuti_confirm['total_cuti'] ?></h3>
                                    <p>Menunggu</p>
                                </div>
                                <a href="<?= base_url('Cuti/view_pegawai_menunggu') ?>" class="small-box-footer">Detail
                                    <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-gradient-red">
                                <div class="inner">
                                    <h3><?= $cuti_reject['total_cuti'] ?></h3>
                                    <p>Ditolak</p>
                                </div>
                                <a href="<?= base_url('Cuti/view_pegawai_reject') ?>" class="small-box-footer">Detail <i
                                        class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                    <!-- /.row -->

                    <!-- Tambahkan kotak sisa cuti tahunan -->
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="small-box bg-gradient-success">
                                <div class="inner">
                                    <h3><?= $pegawai['sisa_cuti'] ?> <small>hari</small></h3>
                                    <p>Sisa Cuti Tahunan</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <a href="<?= base_url('Cuti/add_cuti_pegawai') ?>" class="small-box-footer">
                                    Ajukan Cuti <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Info tambahan sisa cuti -->
                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <i class="fas fa-info-circle mr-2"></i>
                                        Informasi Cuti Tahunan
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p><strong>Perhatian:</strong> Gunakan cuti Anda dengan bijak untuk menjaga
                                                keseimbangan antara pekerjaan dan kebutuhan pribadi.</p>
                                        </div>
                                    </div>

                                    <?php if (($pegawai['jatah_cuti'] ?? 12) <= 3): ?>
                                        <div class="alert alert-warning mt-3">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-exclamation-circle mr-2"
                                                    style="font-size: 1.5rem; color: #ffc107;"></i>
                                                <div>
                                                    <strong>Perhatian!</strong> Sisa cuti Anda tinggal
                                                    <?= $pegawai['jatah_cuti'] ?? 12 ?> hari.
                                                    Rencanakan penggunaan cuti dengan baik.
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Syarat Cuti -->
                    <div class="row">
                        <div class="col-12">
                            <h4 class="mt-4 mb-3">Informasi dan Syarat Permohonan Cuti</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header card-header-info">
                                    <h3 class="card-title">Cuti Tahunan</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Jatah: 12 Hari Kerja</h5>
                                    <p class="card-text">Diberikan kepada PNS/ASN yang telah bekerja paling kurang 1
                                        tahun secara terus-menerus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header card-header-info">
                                    <h3 class="card-title">Cuti Besar</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Jatah: Paling Lama 3 Bulan</h5>
                                    <p class="card-text">Diberikan kepada PNS/ASN yang telah mengabdi paling singkat 5
                                        tahun secara terus-menerus.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100">
                                <div class="card-header card-header-info">
                                    <h3 class="card-title">Cuti Sakit</h3>
                                </div>
                                <div class="card-body">
                                    <p class="card-text">Diberikan bila PNS/ASN sakit lebih dari 1 hari. Wajib
                                        melampirkan surat keterangan dokter. Untuk sakit lebih dari 14 hari, diperlukan
                                        surat dari Tim Penguji Kesehatan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mt-4">
                            <div class="card h-100">
                                <div class="card-header card-header-info">
                                    <h3 class="card-title">Cuti Melahirkan</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Jatah: 3 Bulan</h5>
                                    <p class="card-text">Diberikan untuk persalinan anak pertama sampai dengan ketiga.
                                        Dapat diambil 1.5 bulan sebelum dan 1.5 bulan sesudah persalinan.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4 mt-4">
                            <div class="card h-100">
                                <div class="card-header card-header-info">
                                    <h3 class="card-title">Cuti Alasan Penting</h3>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Jatah: Paling Lama 1 Bulan</h5>
                                    <p class="card-text">Diberikan ketika anggota keluarga inti (orang tua, mertua,
                                        suami/istri, anak, adik, kakak) sakit keras atau meninggal dunia.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End of Syarat Cuti Section -->

                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->

    <?php $this->load->view("pegawai/components/js.php") ?>
</body>

</html>