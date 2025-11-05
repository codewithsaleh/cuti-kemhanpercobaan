<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            Edit Profil Atasan
            <small>Ubah data profil atasan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo base_url('super_admin/dashboard'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo base_url('super_admin/profil_atasan'); ?>">Profil Atasan</a></li>
            <li class="active">Edit Profil Atasan</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Form Edit Profil Atasan</h3>
                    </div>
                    
                    <form role="form" method="post" action="<?php echo base_url('super_admin/update_profil_atasan'); ?>">
                        <div class="box-body">
                            <input type="hidden" name="id" value="<?php echo $profil['id']; ?>">
                            
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama" value="<?php echo $profil['nama']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>NIP</label>
                                <input type="text" class="form-control" name="nip" value="<?php echo $profil['nip']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Jabatan</label>
                                <input type="text" class="form-control" name="jabatan" value="<?php echo $profil['jabatan']; ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Unit Kerja</label>
                                <input type="text" class="form-control" name="unit_kerja" value="<?php echo $profil['unit_kerja']; ?>" required>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <a href="<?php echo base_url('super_admin/profil_atasan'); ?>" class="btn btn-default">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>