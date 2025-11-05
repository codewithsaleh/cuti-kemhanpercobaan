<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * View: Form Pengajuan Cuti Pegawai
 * File: application/views/pegawai/form_cuti.php
 */

$CI =& get_instance();
$csrf_name = $CI->security->get_csrf_token_name();
$csrf_hash = $CI->security->get_csrf_hash();

$action    = isset($action) ? $action : site_url('pegawai/cuti/simpan');
$back_url  = isset($back_url) ? $back_url : site_url('pegawai/cuti');

$pegawai_id    = isset($pegawai['id']) ? $pegawai['id'] : set_value('pegawai_id');
$pegawai_nip   = isset($pegawai['nip']) ? $pegawai['nip'] : set_value('nip');
$pegawai_nama  = isset($pegawai['nama']) ? $pegawai['nama'] : set_value('nama');
$pegawai_unit  = isset($pegawai['unit']) ? $pegawai['unit'] : set_value('unit');

$sisa_cuti     = isset($sisa_cuti) ? (int)$sisa_cuti : (int)set_value('sisa_cuti', 12);
$jenis_cuti    = set_value('jenis_cuti', isset($data['jenis_cuti']) ? $data['jenis_cuti'] : 'Tahunan');
$tgl_mulai     = set_value('tanggal_mulai', isset($data['tanggal_mulai']) ? $data['tanggal_mulai'] : '');
$tgl_selesai   = set_value('tanggal_selesai', isset($data['tanggal_selesai']) ? $data['tanggal_selesai'] : '');
$alasan        = set_value('alasan', isset($data['alasan']) ? $data['alasan'] : '');
$alamat_cuti   = set_value('alamat_cuti', isset($data['alamat_cuti']) ? $data['alamat_cuti'] : '');
$telepon_cuti  = set_value('telepon_cuti', isset($data['telepon_cuti']) ? $data['telepon_cuti'] : '');
$atasan_id     = set_value('atasan_id', isset($data['atasan_id']) ? $data['atasan_id'] : '');

$jenis_cuti_list = isset($jenis_cuti_list) && is_array($jenis_cuti_list) ? $jenis_cuti_list : [
    'Tahunan' => 'Cuti Tahunan',
    'Sakit' => 'Cuti Sakit',
    'Besar' => 'Cuti Besar',
    'Melahirkan' => 'Cuti Melahirkan',
    'Alasan Penting' => 'Cuti Karena Alasan Penting',
    'Di Luar Tanggungan' => 'Cuti di Luar Tanggungan Negara',
];

$atasan_options = isset($atasan_options) && is_array($atasan_options) ? $atasan_options : [
    '' => '-- Pilih Atasan Langsung --'
];

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Form Pengajuan Cuti</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {font-family: Arial, sans-serif; font-size:14px; color:#222; margin:0; background:#fafafa;}
        .container {max-width: 980px; margin: 24px auto; background:#fff; border:1px solid #e5e5e5; border-radius:8px; padding: 20px;}
        .row {display:flex; flex-wrap:wrap; gap:16px;}
        .col {flex:1 1 320px; min-width:260px;}
        .field {margin-bottom:12px;}
        .field label {display:block; margin-bottom:6px; font-weight:600;}
        .field input[type="text"],
        .field input[type="date"],
        .field input[type="number"],
        .field input[type="file"],
        .field select,
        .field textarea {width:100%; padding:8px 10px; border:1px solid #ccc; border-radius:4px; box-sizing:border-box;}
        .field input[readonly] {background:#f7f7f7;}
        .help {font-size:12px; color:#666; margin-top:4px;}
        .error {color:#b00020; font-size:12px; margin-top:4px;}
        .actions {display:flex; gap:10px; margin-top:16px;}
        .btn {display:inline-block; border-radius:6px; padding:10px 14px; border:1px solid transparent; cursor:pointer; font-weight:600;}
        .btn-primary {background:#0d6efd; color:#fff; border-color:#0d6efd;}
        .btn-secondary {background:#e9ecef; color:#222; border-color:#e9ecef; text-decoration:none; text-align:center;}
        .badge {display:inline-block; background:#eef5ff; color:#0d6efd; border:1px solid #cfe2ff; padding:2px 8px; border-radius:999px; font-size:12px;}
        .header {display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;}
        .title {font-size:18px; font-weight:700;}
        .alert {padding:10px 12px; border-radius:6px; margin-bottom:12px;}
        .alert-danger {background:#fff5f5; border:1px solid #f5c2c7; color:#b02a37;}
        .alert-success {background:#f0fff4; border:1px solid #badbcc; color:#0f5132;}
        .divider {height:1px; background:#eee; margin:12px 0;}
        @media (max-width: 600px){ .header {flex-direction:column; align-items:flex-start; gap:6px;} }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="title">Form Pengajuan Cuti Pegawai</div>
        <div>
            <span class="badge">Sisa Cuti Tahunan: <span id="badge-sisa"><?= (int)$sisa_cuti ?></span> hari</span>
        </div>
    </div>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
    <?php endif; ?>
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <form id="form-cuti" action="<?= esc($action) ?>" method="post" enctype="multipart/form-data" novalidate>
        <input type="hidden" name="<?= esc($csrf_name) ?>" value="<?= esc($csrf_hash) ?>">
        <input type="hidden" name="pegawai_id" value="<?= esc($pegawai_id) ?>">

        <div class="row">
            <div class="col">
                <div class="field">
                    <label>NIP</label>
                    <input type="text" name="nip" value="<?= esc($pegawai_nip) ?>" readonly>
                    <?= form_error('nip', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Nama</label>
                    <input type="text" name="nama" value="<?= esc($pegawai_nama) ?>" readonly>
                    <?= form_error('nama', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Unit/Bagian</label>
                    <input type="text" name="unit" value="<?= esc($pegawai_unit) ?>" readonly>
                    <?= form_error('unit', '<div class="error">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="row">
            <div class="col">
                <div class="field">
                    <label>Jenis Cuti</label>
                    <select name="jenis_cuti" id="jenis_cuti">
                        <?php foreach ($jenis_cuti_list as $key => $label): ?>
                            <?php $val = is_int($key) ? $label : $key; ?>
                            <option value="<?= esc($val) ?>" <?= $jenis_cuti === $val ? 'selected' : '' ?>><?= esc($label) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('jenis_cuti', '<div class="error">', '</div>'); ?>
                    <div class="help">Cuti tahunan akan mengurangi sisa cuti.</div>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="<?= esc($tgl_mulai) ?>" required>
                    <?= form_error('tanggal_mulai', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="<?= esc($tgl_selesai) ?>" required>
                    <?= form_error('tanggal_selesai', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Lama Cuti (hari)</label>
                    <input type="number" name="lama_cuti" id="lama_cuti" value="<?= esc(set_value('lama_cuti')) ?>" readonly>
                    <div class="help" id="lama_help"></div>
                    <?= form_error('lama_cuti', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Sisa Cuti Setelah Pengajuan</label>
                    <input type="number" id="sisa_setelah" value="<?= (int)$sisa_cuti ?>" readonly>
                    <div class="help">Otomatis dihitung untuk jenis Cuti Tahunan.</div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="field">
                    <label>Alamat Selama Cuti</label>
                    <textarea name="alamat_cuti" rows="3" placeholder="Alamat selama cuti"><?= esc($alamat_cuti) ?></textarea>
                    <?= form_error('alamat_cuti', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>No. Telepon yang Bisa Dihubungi</label>
                    <input type="text" name="telepon_cuti" value="<?= esc($telepon_cuti) ?>" placeholder="08xxxxxxxxxx">
                    <?= form_error('telepon_cuti', '<div class="error">', '</div>'); ?>
                </div>
            </div>
            <div class="col">
                <div class="field">
                    <label>Atasan Langsung</label>
                    <select name="atasan_id" id="atasan_id">
                        <?php foreach ($atasan_options as $val => $text): ?>
                            <option value="<?= esc($val) ?>" <?= (string)$atasan_id === (string)$val ? 'selected' : '' ?>><?= esc($text) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?= form_error('atasan_id', '<div class="error">', '</div>'); ?>
                </div>
            </div>
        </div>

        <div class="field">
            <label>Alasan/Keterangan</label>
            <textarea name="alasan" rows="4" placeholder="Alasan pengajuan cuti"><?= esc($alasan) ?></textarea>
            <?= form_error('alasan', '<div class="error">', '</div>'); ?>
        </div>

        <div class="field">
            <label>Lampiran (opsional)</label>
            <input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png">
            <div class="help">PDF/JPG/PNG, maks 2MB.</div>
            <?= form_error('lampiran', '<div class="error">', '</div>'); ?>
        </div>

        <div class="actions">
            <button type="submit" class="btn btn-primary">Ajukan Cuti</button>
            <a href="<?= esc($back_url) ?>" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>

<script>
(function(){
    var jenisCutiEl = document.getElementById('jenis_cuti');
    var tglMulaiEl = document.getElementById('tanggal_mulai');
    var tglSelesaiEl = document.getElementById('tanggal_selesai');
    var lamaEl = document.getElementById('lama_cuti');
    var lamaHelpEl = document.getElementById('lama_help');
    var sisaSetelahEl = document.getElementById('sisa_setelah');
    var badgeSisaEl = document.getElementById('badge-sisa');

    var sisaAwal = parseInt(<?= (int)$sisa_cuti ?>, 10);

    function parseDate(val) {
        if (!val) return null;
        var d = new Date(val + 'T00:00:00');
        return isNaN(d.getTime()) ? null : d;
    }

    function countDaysInclusive(from, to) {
        var msPerDay = 24*60*60*1000;
        var utc1 = Date.UTC(from.getFullYear(), from.getMonth(), from.getDate());
        var utc2 = Date.UTC(to.getFullYear(), to.getMonth(), to.getDate());
        var days = Math.floor((utc2 - utc1)/msPerDay) + 1;
        return days;
    }

    function updateLama() {
        var d1 = parseDate(tglMulaiEl.value);
        var d2 = parseDate(tglSelesaiEl.value);
        var jenis = (jenisCutiEl.value || '').toLowerCase();
        lamaHelpEl.textContent = '';

        if (!d1 || !d2) {
            lamaEl.value = '';
            sisaSetelahEl.value = sisaAwal;
            return;
        }
        if (d2 < d1) {
            lamaEl.value = '';
            lamaHelpEl.textContent = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
            sisaSetelahEl.value = sisaAwal;
            return;
        }

        var hari = countDaysInclusive(d1, d2);
        lamaEl.value = hari;

        if (jenis === 'tahunan') {
            var sisa = sisaAwal - hari;
            sisaSetelahEl.value = sisa >= 0 ? sisa : 0;
        } else {
            sisaSetelahEl.value = sisaAwal;
        }
    }

    function onJenisChange() {
        updateLama();
    }

    jenisCutiEl.addEventListener('change', onJenisChange);
    tglMulaiEl.addEventListener('change', updateLama);
    tglSelesaiEl.addEventListener('change', updateLama);

    updateLama();

    var form = document.getElementById('form-cuti');
    form.addEventListener('submit', function(e){
        var d1 = parseDate(tglMulaiEl.value);
        var d2 = parseDate(tglSelesaiEl.value);
        if (!d1 || !d2) return;

        if (d2 < d1) {
            e.preventDefault();
            alert('Periksa tanggal mulai dan tanggal selesai.');
            return;
        }
        var jenis = (jenisCutiEl.value || '').toLowerCase();
        var lama = parseInt(lamaEl.value || '0', 10);
        if (jenis === 'tahunan' && lama > sisaAwal) {
            e.preventDefault();
            alert('Lama cuti melebihi sisa cuti tahunan.');
        }
    });
})();
</script>
</body>
</html>