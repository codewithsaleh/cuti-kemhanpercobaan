<!DOCTYPE html>
<html lang="en">
<head>
    <?php $this->load->view('super_admin/components/header');?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <?php $this->load->view('super_admin/components/navbar');?>
        <?php $this->load->view('super_admin/components/sidebar');?>
        <style>
        #example1 thead th {
            background-color: #4e73df; 
            color: white; 
        }
    </style>
        
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Tim Anda</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('Atasan/dashboard') ?>">Home</a></li>
                                <li class="breadcrumb-item active">Data Tim</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Pegawai</h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Lengkap</th>
                                                <th>NIP / NRP</th>
                                                <th>Pangkat</th>
                                                <th>Jabatan</th>
                                                <th>Sisa Cuti</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $no = 0;
                                            foreach($pegawai_list as $pegawai):
                                                $no++;
                                            ?>
                                            <tr>
                                                <td><?= $no ?></td>
                                                <td><?= htmlspecialchars($pegawai['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($pegawai['nip'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($pegawai['pangkat'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($pegawai['jabatan'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars($pegawai['jatah_cuti'], ENT_QUOTES, 'UTF-8') ?> Hari</td>
                                            </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <footer class="main-footer">
            <strong>Copyright &copy; 2025 <a href="#">SI Cuti Kemhan</a>.</strong>
        </footer>
    </div>
    
    <?php $this->load->view('super_admin/components/js');?>

    <!-- Script untuk inisialisasi DataTable -->
    <script>
    $(function () {
        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }
        $("#example1").DataTable({
            "responsive": true, 
            "lengthChange": true, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
    </script>
</body>
</html>

