<!DOCTYPE html>
<html lang="en">

<head>
    <?php $this->load->view("pegawai/components/header.php") ?>

<!-- [MODIFIKASI BARU] CSS untuk memperbaiki layout tabel agar tidak melebar -->
    <style>
        /* Gaya Desktop (Default) */
        .chat-container {
            display: flex; height: 75vh; border: 1px solid #dee2e6;
            border-radius: 0.5rem; overflow: hidden; background-color: #ffffff;
        }
        .chat-sidebar {
            width: 300px; border-right: 1px solid #dee2e6; background-color: #fff;
            display: flex; flex-direction: column; flex-shrink: 0;
        }
        .chat-sidebar-header {
            padding: 1.25rem; font-weight: 600; border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .chat-user-list { overflow-y: auto; flex-grow: 1; }
        .chat-user {
            display: flex; align-items: center; padding: 1rem 1.25rem;
            cursor: pointer; border-bottom: 1px solid #f1f1f1; transition: background-color 0.2s;
        }
        .chat-user:hover { background-color: #f8f9fa; }
        .chat-user.active { background-color: #007bff; color: white; }
        .chat-user.active .title { color: #e3e3e3; }
        .chat-user img {
            width: 40px; height: 40px; border-radius: 50%; margin-right: 1rem;
        }
        .chat-user-info .name { font-weight: 600; }
        .chat-user-info .title { font-size: 0.8em; color: #6c757d; }
        .chat-main {
            flex-grow: 1; display: flex; flex-direction: column; background-color: #f4f6f9;
        }
        .chat-header {
            padding: 1.25rem; border-bottom: 1px solid #dee2e6;
            background-color: #fff; font-weight: 600; display: flex; align-items: center;
        }
        .chat-body {
            flex-grow: 1; padding: 1.25rem; overflow-y: auto;
            display: flex; flex-direction: column;
        }
        .chat-message {
            display: flex; flex-direction: column; margin-bottom: 1rem; max-width: 70%;
        }
        .chat-message.sent { align-self: flex-end; align-items: flex-end; }
        .chat-message.received { align-self: flex-start; align-items: flex-start; }
        .message-bubble { padding: 0.75rem 1rem; border-radius: 1.25rem; }
        .sent .message-bubble { background-color: #007bff; color: #fff; border-bottom-right-radius: 0.25rem; }
        .received .message-bubble {
            background-color: #fff; color: #333; border: 1px solid #dee2e6; border-bottom-left-radius: 0.25rem;
        }
        .message-time {
            font-size: 0.75em; color: #6c757d; margin-top: 0.25rem; padding: 0 0.25rem;
        }
        .chat-footer {
            padding: 1rem 1.25rem; background-color: #fff; border-top: 1px solid #dee2e6;
        }
        #no-chat-selected {
            display: flex; justify-content: center; align-items: center; height: 100%;
            flex-direction: column; color: #6c757d; text-align: center;
        }
        #no-chat-selected i { font-size: 4em; margin-bottom: 1rem; }
        .message-bubble-container { display: flex; align-items: center; }
        .delete-icon {
            color: #6c757d; opacity: 0.5; margin-left: 10px; cursor: pointer; transition: opacity 0.2s;
        }
        .delete-icon:hover { opacity: 1; }
        .message-bubble.deleted {
            background-color: #f8f9fa !important; color: #6c757d !important;
            font-style: italic; border: 1px solid #dee2e6 !important;
        }

        /* [BARU] Tombol kembali, disembunyikan di desktop */
        .back-to-users { display: none; }

        /*
         * =================================================================
         * [PEROMBAKAN TOTAL] Tampilan Mobile (Layar di bawah 768px)
         * =================================================================
         */
        @media (max-width: 767.98px) {
            .chat-container {
                height: calc(100vh - 140px); /* Sesuaikan tinggi untuk mobile */
            }
            
            /* Default: Tampilkan daftar pengguna, sembunyikan chat */
            .chat-sidebar {
                width: 100%;
                border-right: none;
            }
            .chat-main {
                display: none;
            }

            /* Saat chat aktif: Sembunyikan daftar pengguna, tampilkan chat */
            .chat-container.mobile-chat-active .chat-sidebar {
                display: none;
            }
            .chat-container.mobile-chat-active .chat-main {
                display: flex;
                width: 100%;
            }

            /* Tampilkan dan atur gaya tombol kembali */
            .back-to-users {
                display: block;
                margin-right: 15px;
                font-size: 1.2rem;
                cursor: pointer;
                color: #6c757d;
            }
        }
    
/* =======================
 * Tampilan Tablet (768px - 991.98px)
 * ======================= */
@media (min-width: 768px) and (max-width: 991.98px) {
    /* Ukuran dan layout container */
    .chat-container {
        flex-direction: column;
        height: auto;
    }

    .chat-sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 1px solid #dee2e6;
    }

    .chat-main {
        width: 100%;
    }

    /* Ukuran font dan padding agar pas di layar tablet */
    .chat-user {
        padding: 0.75rem 1rem;
    }

    .chat-header, .chat-footer {
        padding: 1rem;
    }

    .chat-body {
        padding: 1rem;
    }

    .message-bubble {
        font-size: 0.95rem;
    }

    .message-time {
        font-size: 0.75rem;
    }

    /* Tabel DataTables di tablet */
    table.table th,
    table.table td {
        font-size: 0.9rem;
        padding: 0.6rem;
        word-break: break-word;
    }

    .dataTables_wrapper .row {
        flex-direction: column;
        gap: 0.5rem;
    }

    .dataTables_length label,
    .dataTables_filter label {
        flex-direction: column;
        align-items: flex-start;
    }

    .btn-group .btn {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }

    /* Modal di tablet */
    .modal-dialog {
        max-width: 90%;
        margin: 1.5rem auto;
    }
}

/* Tablet - toggle sidebar */
@media (min-width: 768px) and (max-width: 991.98px) {
    .sidebar-collapsed .main-sidebar {
        transform: translateX(-100%);
    }

    .main-sidebar {
        transition: transform 0.3s ease;
        z-index: 1050;
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        width: 250px;
        background-color: #343a40;
    }

    .content-wrapper {
        margin-left: 0 !important;
    }

            /* Default: Tampilkan daftar pengguna, sembunyikan chat */
            .chat-sidebar {
                width: 100%;
                border-right: none;
            }
            .chat-main {
                display: none;
            }

            /* Saat chat aktif: Sembunyikan daftar pengguna, tampilkan chat */
            .chat-container.mobile-chat-active .chat-sidebar {
                display: none;
            }
            .chat-container.mobile-chat-active .chat-main {
                display: flex;
                width: 100%;
            }

            /* Tampilkan dan atur gaya tombol kembali */
            .back-to-users {
                display: block;
                margin-right: 15px;
                font-size: 1.2rem;
                cursor: pointer;
                color: #6c757d;
            }
        }

        /* Memberi lebar spesifik pada kolom berdasarkan posisinya */
        #example1 th:nth-child(1) { width: 4%; }   /* No */
        #example1 th:nth-child(2) { width: 14%; }  /* Nama */
        #example1 th:nth-child(3) { width: 12%; }  /* Jenis Cuti */
        #example1 th:nth-child(4) { width: 15%; }  /* Perihal */
        #example1 th:nth-child(5) { width: 20%; }  /* Alasan */
        #example1 th:nth-child(6) { width: 9%; }   /* Mulai */
        #example1 th:nth-child(7) { width: 9%; }   /* Berakhir */
        #example1 th:nth-child(8) { width: 8%; }   /* Status */
        #example1 th:nth-child(9) { width: 9%; }   /* Aksi */
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">

    <?php if ($this->session->flashdata('hapus')){ ?>
    <script>
        swal({ title: "Success!", text: "Data Berhasil Dihapus!", icon: "success" });
    </script>
    <?php } ?>

    <?php if ($this->session->flashdata('eror_hapus')){ ?>
    <script>
        swal({ title: "Error!", text: "Data Gagal Dihapus!", icon: "error" });
    </script>
    <?php } ?>

    <div class="wrapper">
        <?php $this->load->view("pegawai/components/navbar.php") ?>
        <?php $this->load->view("pegawai/components/sidebar.php") ?>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Riwayat Pengajuan Cuti</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item active">Data Cuti</li>
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
                                    <h3 class="card-title">Data Cuti Saya</h3>
                                    <style>
        #example1 thead th {
            background-color: #4e73df; 
            color: white; 
        }
    </style>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="example1" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Jenis Cuti</th>
                                                    <th>Perihal</th>
                                                    <th>Alasan</th>
                                                    <th>Mulai</th>
                                                    <th>Berakhir</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $no = 0;
                                                foreach($cuti as $i):
                                                    $no++;
                                                    $id_cuti = $i['id_cuti'];
                                                    $id_user = $i['id_user'];
                                                    $nama_cuti = (!empty($i['nama_cuti'])) ? $i['nama_cuti'] : 'N/A';
                                                    $perihal_cuti = $i['perihal_cuti'];
                                                    $alasan = $i['alasan'];
                                                    $mulai = $i['mulai'];
                                                    $berakhir = $i['berakhir'];
                                                    $id_status_cuti = $i['id_status_cuti'];
                                                ?>
                                                <tr>
                                                    <td><?= $no ?></td>
                                                    <td><span class="badge badge-info"><?= $nama_cuti ?></span></td>
                                                    <td><?= $perihal_cuti ?></td>
                                                    <td><?= $alasan ?></td>
                                                    <td><?= date('d M Y', strtotime($mulai)) ?></td>
                                                    <td><?= date('d M Y', strtotime($berakhir)) ?></td>
                                                    <td>
                                                        <?php if($id_status_cuti == 1){ ?>
                                                            <span class="badge badge-warning">Menunggu</span>
                                                        <?php } elseif($id_status_cuti == 2) { ?>
                                                            <span class="badge badge-success">Diterima</span>
                                                        <?php } elseif($id_status_cuti == 3) { ?>
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <?php if($id_status_cuti == 2): ?>
                                                                <a href="<?= base_url();?>Cetak/surat_cuti_pdf/<?=$id_cuti?>" target="_blank" class="btn btn-sm btn-info" title="Cetak Surat">
                                                                    <i class="fas fa-print"></i>
                                                                </a>
                                                            <?php endif; ?>
                                                            <button data-toggle="modal" data-target="#hapus<?= $id_cuti ?>" class="btn btn-sm btn-danger" title="Hapus Pengajuan">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                
                                                <div class="modal fade" id="hapus<?= $id_cuti ?>" tabindex="-1">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Hapus Data Cuti</h5>
                                                                <button type="button" class="close" data-dismiss="modal">
                                                                    <span>&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="<?= base_url()?>Cuti/hapus_cuti" method="post">
                                                                    <input type="hidden" name="id_cuti" value="<?= $id_cuti ?>" />
                                                                    <input type="hidden" name="id_user" value="<?= $id_user ?>" />
                                                                    <p>Apakah Anda yakin ingin menghapus data pengajuan cuti ini?</p>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                                                                        <button type="submit" class="btn btn-danger">Ya</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endforeach;?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </section>
        </div>
    </div>

    <?php $this->load->view("pegawai/components/js.php") ?>

    <script>
    $(function() {
        if ($.fn.DataTable.isDataTable('#example1')) {
            $('#example1').DataTable().destroy();
        }

        $("#example1").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "buttons": ["csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
    </script>
</body>

</html>
