<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Manajemen Cuti Tim
            <small>Data Pengajuan Cuti</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('super_admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Cuti Tim</li>
        </ol>
    </section>

    <section class="content">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Daftar Pengajuan Cuti</h3>
            </div>
            <div class="box-body">
                <?php echo $this->session->flashdata('message'); ?>
                <table id="tableCuti" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pegawai</th>
                            <th>Jenis Cuti</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Jumlah Hari</th>
                            <th>Alasan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        foreach($cuti_list as $cuti): 
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $cuti->nama_pegawai; ?></td>
                            <td><?php echo $cuti->jenis_cuti; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($cuti->tanggal_mulai)); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($cuti->tanggal_selesai)); ?></td>
                            <td><?php echo $cuti->jumlah_hari; ?> hari</td>
                            <td><?php echo $cuti->alasan; ?></td>
                            <td>
                                <?php if($cuti->status == 'pending'): ?>
                                    <span class="label label-warning">Pending</span>
                                <?php elseif($cuti->status == 'approved'): ?>
                                    <span class="label label-success">Disetujui</span>
                                <?php else: ?>
                                    <span class="label label-danger">Ditolak</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo base_url('super_admin/detail_cuti/'.$cuti->id); ?>" class="btn btn-info btn-sm">
                                    <i class="fa fa-eye"></i> Detail
                                </a>
                                <?php if($cuti->status == 'pending'): ?>
                                <a href="<?php echo base_url('super_admin/approve_cuti/'.$cuti->id); ?>" class="btn btn-success btn-sm" onclick="return confirm('Setujui pengajuan cuti ini?')">
                                    <i class="fa fa-check"></i> Setujui
                                </a>
                                <a href="<?php echo base_url('super_admin/reject_cuti/'.$cuti->id); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tolak pengajuan cuti ini?')">
                                    <i class="fa fa-times"></i> Tolak
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('#tableCuti').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>