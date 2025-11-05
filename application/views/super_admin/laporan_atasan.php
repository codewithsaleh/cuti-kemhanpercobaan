<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Atasan - Sistem Cuti Kemhan</title>
</head>
<body>
    <div class="container">
        <h2>Laporan Pengajuan Cuti - Atasan</h2>
        
        <div class="filter-section">
            <form method="get" action="<?php echo base_url('super_admin/laporan_atasan'); ?>">
                <div class="row">
                    <div class="col-md-3">
                        <label>Dari Tanggal:</label>
                        <input type="date" name="dari_tanggal" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Sampai Tanggal:</label>
                        <input type="date" name="sampai_tanggal" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label>Status:</label>
                        <select name="status" class="form-control">
                            <option value="">Semua</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Disetujui</option>
                            <option value="rejected">Ditolak</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Pegawai</th>
                        <th>Jenis Cuti</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Selesai</th>
                        <th>Durasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($laporan)): ?>
                        <?php foreach($laporan as $key => $row): ?>
                        <tr>
                            <td><?php echo $key + 1; ?></td>
                            <td><?php echo $row->nama_pegawai; ?></td>
                            <td><?php echo $row->jenis_cuti; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row->tanggal_mulai)); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($row->tanggal_selesai)); ?></td>
                            <td><?php echo $row->durasi; ?> hari</td>
                            <td><?php echo $row->status; ?></td>
                            <td>
                                <a href="<?php echo base_url('super_admin/detail_cuti/'.$row->id); ?>" class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>