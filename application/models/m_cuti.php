<?php

class M_cuti extends CI_Model
{

    public function get_all_cuti()
    {
        $hasil = $this->db->query('
            SELECT cuti.*, user_detail.nama_lengkap, jenis_cuti.nama_cuti, status_cuti.status_cuti 
            FROM cuti 
            JOIN user ON cuti.id_user = user.id_user 
            JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail 
            LEFT JOIN jenis_cuti ON cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti 
            LEFT JOIN status_cuti ON cuti.id_status_cuti = status_cuti.id_status_cuti
            ORDER BY cuti.tgl_diajukan DESC');
        return $hasil;
    }

    public function get_cuti_for_admin()
    {
        $hasil = $this->db->query('
            SELECT cuti.*, user_detail.nama_lengkap, jenis_cuti.nama_cuti, status_cuti.status_cuti
            FROM cuti 
            JOIN user ON cuti.id_user = user.id_user 
            JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail 
            LEFT JOIN jenis_cuti ON cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti 
            LEFT JOIN status_cuti ON cuti.id_status_cuti = status_cuti.id_status_cuti
            WHERE cuti.id_status_cuti = 4
            ORDER BY cuti.tgl_diajukan DESC');
        return $hasil;
    }

    public function get_all_cuti_by_id_user($id_user)
    {
        $hasil = $this->db->query("
            SELECT cuti.*, user_detail.nama_lengkap, jenis_cuti.nama_cuti, status_cuti.status_cuti 
            FROM cuti 
            JOIN user ON cuti.id_user = user.id_user 
            JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail 
            LEFT JOIN jenis_cuti ON cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti 
            LEFT JOIN status_cuti ON cuti.id_status_cuti = status_cuti.id_status_cuti
            WHERE cuti.id_user='$id_user' 
            ORDER BY cuti.tgl_diajukan DESC");
        return $hasil;
    }

    public function get_all_cuti_first_by_id_user($id_user)
    {
        $hasil = $this->db->query("SELECT * FROM cuti JOIN user ON cuti.id_user = user.id_user JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail WHERE cuti.id_user='$id_user' AND cuti.id_status_cuti='2' ORDER BY cuti.tgl_diajukan DESC LIMIT 1");
        return $hasil;
    }

    public function get_all_cuti_by_id_cuti($id_cuti)
    {
        $hasil = $this->db->query("
            SELECT * FROM cuti 
            JOIN user ON cuti.id_user = user.id_user 
            JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail
            LEFT JOIN jenis_cuti ON cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti
            WHERE cuti.id_cuti='$id_cuti'
        ");
        return $hasil;
    }

    public function insert_data_cuti($id_cuti, $id_user, $id_jenis_cuti, $alasan, $mulai, $berakhir, $id_status_cuti, $perihal_cuti)
    {
        $this->db->trans_start();
        $this->db->query("INSERT INTO cuti(id_cuti, id_user, id_jenis_cuti, alasan, tgl_diajukan, mulai, berakhir, id_status_cuti, perihal_cuti) VALUES ('$id_cuti','$id_user', '$id_jenis_cuti','$alasan',NOW(),'$mulai', '$berakhir', '$id_status_cuti', '$perihal_cuti')");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_cuti($id_cuti)
    {
        $this->db->trans_start();
        $this->db->query("DELETE FROM cuti WHERE id_cuti='$id_cuti'");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function update_cuti($alasan, $perihal_cuti, $tgl_diajukan, $mulai, $berakhir, $id_cuti)
    {
        $this->db->trans_start();
        $this->db->query("UPDATE cuti SET alasan='$alasan', perihal_cuti='$perihal_cuti', tgl_diajukan='$tgl_diajukan', mulai='$mulai', berakhir='$berakhir' WHERE id_cuti='$id_cuti'");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function confirm_cuti($id_cuti, $id_status_cuti, $alasan_verifikasi = '', $alasan_penolakan = '')
    {
        $this->db->trans_start();

        // SET TIMEZONE DULU
        date_default_timezone_set('Asia/Jakarta');
        $current_time = date('Y-m-d H:i:s');

        // **GET CUTI DETAIL dengan JOIN yang benar**
        $cuti_detail = $this->db->select('c.*, u.id_user_detail')
            ->from('cuti c')
            ->join('user u', 'c.id_user = u.id_user')
            ->where('c.id_cuti', $id_cuti)
            ->get()
            ->row();

        // **KURANGI SISA CUTI JIKA DISETUJUI**
        if ($cuti_detail && $id_status_cuti == 2 && $cuti_detail->id_jenis_cuti == 1) {
            $tglMulai = new DateTime($cuti_detail->mulai);
            $tglBerakhir = new DateTime($cuti_detail->berakhir);
            $durasi = $tglBerakhir->diff($tglMulai)->days + 1;

            // **UPDATE SISA CUTI - Cara yang lebih reliable**
            $this->db->set('sisa_cuti', 'sisa_cuti - ' . $durasi, FALSE);
            $this->db->where('id_user_detail', $cuti_detail->id_user_detail);
            $this->db->update('user_detail');

            log_message('info', 'Sisa cuti dikurangi: ' . $durasi . ' hari untuk user_detail: ' . $cuti_detail->id_user_detail);
        }

        // **UPDATE STATUS CUTI**
        $update_data = [
            'id_status_cuti' => $id_status_cuti,
            'approved_by' => $this->session->userdata('id_user'),
            'approved_at' => $current_time, // WIB time
            'updated_at' => $current_time
        ];

        if ($id_status_cuti == 2) {
            $update_data['alasan_verifikasi'] = $alasan_verifikasi;
        } elseif ($id_status_cuti == 3) {
            $update_data['alasan_penolakan'] = $alasan_penolakan;
        }

        $this->db->where('id_cuti', $id_cuti);
        $this->db->update('cuti', $update_data);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    // --- FUNGSI PENGHITUNGAN (COUNT) ---

    public function count_all_cuti()
    {
        $this->db->select('COUNT(cuti.id_cuti) as total_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        return $this->db->get();
    }

    public function count_all_cuti_acc()
    {
        $this->db->select('COUNT(cuti.id_cuti) as total_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->where('cuti.id_status_cuti', 2);
        return $this->db->get();
    }

    public function count_all_cuti_confirm()
    {
        $this->db->select('COUNT(cuti.id_cuti) as total_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->where('cuti.id_status_cuti', 4);
        return $this->db->get();
    }

    public function count_all_cuti_reject()
    {
        $this->db->select('COUNT(cuti.id_cuti) as total_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->where('cuti.id_status_cuti', 3);
        return $this->db->get();
    }

    public function count_all_cuti_by_id($id_user)
    {
        $hasil = $this->db->query("SELECT COUNT(id_cuti) as total_cuti FROM cuti WHERE cuti.id_user='$id_user'");
        return $hasil;
    }

    public function count_all_cuti_acc_by_id($id_user)
    {
        $hasil = $this->db->query("SELECT COUNT(id_cuti) as total_cuti FROM cuti WHERE id_status_cuti=2 AND cuti.id_user='$id_user'");
        return $hasil;
    }

    public function count_all_cuti_confirm_by_id($id_user)
    {
        $hasil = $this->db->query("SELECT COUNT(id_cuti) as total_cuti FROM cuti WHERE id_status_cuti=1 AND cuti.id_user='$id_user'");
        return $hasil;
    }

    public function count_all_cuti_reject_by_id($id_user)
    {
        $hasil = $this->db->query("SELECT COUNT(id_cuti) as total_cuti FROM cuti WHERE id_status_cuti=3 AND cuti.id_user='$id_user'");
        return $hasil;
    }

    public function get_approved_cuti_for_calendar()
    {
        $this->db->select("user_detail.nama_lengkap as title, cuti.mulai as start, DATE_ADD(cuti.berakhir, INTERVAL 1 DAY) as end");
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail');
        $this->db->where('cuti.id_status_cuti', 2);
        $query = $this->db->get();
        return $query->result();
    }

    // --- FUNGSI UNTUK ATASAN ---

    public function count_cuti_bawahan_by_status($id_atasan, $id_status_cuti)
    {
        $hasil = $this->db->query("
            SELECT COUNT(cuti.id_cuti) as total_cuti 
            FROM cuti
            JOIN user ON cuti.id_user = user.id_user
            WHERE user.id_atasan = '$id_atasan' 
            AND cuti.id_status_cuti = '$id_status_cuti'
        ");
        return $hasil;
    }

    public function count_all_cuti_bawahan($id_atasan)
    {
        $this->db->select('COUNT(cuti.id_cuti) as total_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->where('user.id_atasan', $id_atasan);
        return $this->db->get();
    }

    public function update_status_saja($id_cuti, $id_status_cuti)
    {
        $this->db->trans_start();
        $this->db->query("UPDATE cuti SET id_status_cuti='$id_status_cuti' WHERE id_cuti='$id_cuti'");
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_cuti_by_status($id_status_cuti)
    {
        $this->db->select('cuti.*, user_detail.nama_lengkap, jenis_cuti.nama_cuti, status_cuti.status_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail');
        $this->db->join('jenis_cuti', 'cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti', 'left');
        $this->db->join('status_cuti', 'cuti.id_status_cuti = status_cuti.id_status_cuti', 'left');
        $this->db->where('cuti.id_status_cuti', $id_status_cuti);
        $this->db->order_by('cuti.tgl_diajukan', 'DESC');
        return $this->db->get();
    }

    public function get_cuti_bawahan_by_date_range($id_atasan, $start_date, $end_date)
    {
        $this->db->select("
            cuti.*, 
            user_detail.nama_lengkap, 
            user_detail.nip, 
            jenis_cuti.nama_cuti,
            status_cuti.status_cuti,
            DATEDIFF(cuti.berakhir, cuti.mulai) + 1 AS lama_cuti
        ");
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail');
        $this->db->join('jenis_cuti', 'cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti', 'left');
        $this->db->join('status_cuti', 'cuti.id_status_cuti = status_cuti.id_status_cuti', 'left');

        $this->db->where('user.id_atasan', $id_atasan);

        if (!empty($start_date)) {
            $this->db->where('cuti.mulai >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('cuti.berakhir <=', $end_date);
        }

        $this->db->order_by('cuti.mulai', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_cuti_by_date_range($start_date, $end_date)
    {
        $this->db->select("
        cuti.*, 
        user_detail.nama_lengkap, 
        user_detail.nip, 
        jenis_cuti.nama_cuti,
        status_cuti.status_cuti,
        DATEDIFF(cuti.berakhir, cuti.mulai) + 1 AS lama_cuti
    ");
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail');
        $this->db->join('jenis_cuti', 'cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti', 'left');
        $this->db->join('status_cuti', 'cuti.id_status_cuti = status_cuti.id_status_cuti', 'left');

        // Filter berdasarkan tanggal
        if (!empty($start_date)) {
            $this->db->where('cuti.mulai >=', $start_date);
        }
        if (!empty($end_date)) {
            $this->db->where('cuti.berakhir <=', $end_date);
        }

        $this->db->order_by('cuti.mulai', 'ASC');
        return $this->db->get()->result_array();
    }

    /**
     * [FUNGSI BARU] Mengambil daftar cuti bawahan berdasarkan status.
     * Mirip dengan get_cuti_by_status, tetapi dengan filter tambahan 'id_atasan'.
     */
    public function get_cuti_bawahan_by_status($id_atasan, $id_status_cuti)
    {
        $this->db->select('cuti.*, user_detail.nama_lengkap, user_detail.nip, jenis_cuti.nama_cuti, status_cuti.status_cuti');
        $this->db->from('cuti');
        $this->db->join('user', 'cuti.id_user = user.id_user');
        $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail');
        $this->db->join('jenis_cuti', 'cuti.id_jenis_cuti = jenis_cuti.id_jenis_cuti', 'left');
        $this->db->join('status_cuti', 'cuti.id_status_cuti = status_cuti.id_status_cuti', 'left');

        // Filter utama: hanya untuk bawahan atasan yang sedang login
        $this->db->where('user.id_atasan', $id_atasan);

        // Filter status
        $this->db->where('cuti.id_status_cuti', $id_status_cuti);

        $this->db->order_by('cuti.tgl_diajukan', 'DESC');
        return $this->db->get();
    }

    public function reset_cuti_by_id_user($id_user)
    {
        $this->db->trans_start();

        // Dapatkan data user
        $user = $this->db->get_where('user', ['id_user' => $id_user])->row_array();

        if (!$user) {
            return false;
        }

        $id_user_detail = $user['id_user_detail'];

        // Reset cuti di user_detail
        $reset_data = [
            'sisa_cuti' => 12,
            'jatah_cuti' => 12,
            'tahun_cuti' => date('Y'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->db->where('id_user_detail', $id_user_detail);
        $this->db->update('user_detail', $reset_data);

        // Jika perlu update tabel user juga (tapi kolom cuti tidak ada di user)
        // $this->db->where('id_user', $id_user);
        // $this->db->update('user', ['updated_at' => date('Y-m-d H:i:s')]);

        $this->db->trans_complete();

        return $this->db->trans_status();
    }

    //Resert Cuti Settings Admin:
    // Di application/models/M_cuti.php
    public function reset_cuti_tahunan()
    {
        $this->db->trans_start();

        try {
            $current_year = date('Y');

            // 1. Reset jatah_cuti DAN sisa_cuti menjadi 12 untuk SEMUA user_detail
            $this->db->set('jatah_cuti', 12);
            $this->db->set('sisa_cuti', 12);
            $this->db->set('tahun_cuti', $current_year);
            $this->db->update('user_detail');

            log_message('info', 'Reset cuti: jatah_cuti dan sisa_cuti di-set ke 12 untuk semua user');

            // 2. BACKUP STATUS TERAKHIR sebelum diarsipkan (untuk SEMUA status)
            $this->db->set('status_terakhir', 'id_status_cuti', FALSE);
            $this->db->set('tahun_reset', $current_year);
            $this->db->where('YEAR(mulai)', $current_year); // Hanya cuti tahun berjalan
            $this->db->where_in('id_status_cuti', [1, 2, 3, 4]); // Backup semua status aktif
            $this->db->update('cuti');

            // 3. Update SEMUA status cuti menjadi arsip (5) untuk tahun berjalan
            $this->db->set('id_status_cuti', 5);
            $this->db->where('YEAR(mulai)', $current_year);
            $this->db->where_in('id_status_cuti', [1, 2, 3, 4]); // Hanya status aktif yang diarsipkan
            $this->db->update('cuti');

            log_message('info', 'Reset cuti: semua status cuti tahun ' . $current_year . ' diubah menjadi arsip');

            $this->db->trans_complete();

            return $this->db->trans_status();

        } catch (Exception $e) {
            $this->db->trans_rollback();
            log_message('error', 'Reset cuti gagal: ' . $e->getMessage());
            return false;
        }
    }

    // Function untuk reset otomatis
    public function check_and_reset_auto()
    {
        try {
            $current_year = date('Y');
            $this->load->model('m_settings');
            $last_reset = $this->m_settings->get_setting('last_reset_year');

            if ($last_reset != $current_year) {
                $result = $this->reset_cuti_tahunan();

                if ($result) {
                    $this->m_settings->update_setting('last_reset_year', $current_year);
                    log_message('info', 'Reset cuti OTOMATIS berhasil untuk tahun ' . $current_year);
                }

                return $result;
            }

            return true;
        } catch (Exception $e) {
            log_message('error', 'Auto reset gagal: ' . $e->getMessage());
            return false;
        }
    }

    // Surat Pegawai
    public function get_cuti_by_id_for_surat($id_cuti)
    {
        $this->db->select('
        c.*, 
        u.username,
        ud.nama_lengkap,
        ud.nip,
        ud.pangkat,
        ud.jabatan,
        jc.nama_cuti,
        sc.status_cuti
    ');
        $this->db->from('cuti c');
        $this->db->join('user u', 'c.id_user = u.id_user');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti');
        $this->db->where('c.id_cuti', $id_cuti);

        return $this->db->get()->row_array();
    }

    //Laporan Superadmin
    public function get_cuti_tim_bulanan($id_atasan, $tahun)
    {
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        ud.nama_lengkap,
        ud.nip,
        u.id_atasan,
        DATEDIFF(c.berakhir, c.mulai) + 1 as lama_cuti
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('u.id_atasan', $id_atasan);
        $this->db->where('YEAR(c.tgl_diajukan)', $tahun);
        $this->db->where('c.id_status_cuti >=', 1);  // Status mulai dari 1
        $this->db->where('c.id_status_cuti <=', 4);  // Status sampai 4
        $this->db->order_by('c.tgl_diajukan', 'DESC');

        return $this->db->get()->result_array();
    }

    public function get_cuti_tim_tahunan($id_atasan, $tahun)
    {
        $this->db->select('
        c.*,
        jc.nama_cuti,
        sc.status_cuti,
        ud.nama_lengkap,
        ud.nip,
        u.id_atasan,
        DATEDIFF(c.berakhir, c.mulai) + 1 as lama_cuti
    ');
        $this->db->from('cuti c');
        $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti', 'left');
        $this->db->join('status_cuti sc', 'c.id_status_cuti = sc.id_status_cuti', 'left');
        $this->db->join('user u', 'c.id_user = u.id_user', 'left');
        $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail', 'left');
        $this->db->where('u.id_atasan', $id_atasan);
        $this->db->where('YEAR(c.tgl_diajukan)', $tahun);
        $this->db->where_in('c.id_status_cuti', [1, 2, 3, 4]);  // Hanya status 1-4
        $this->db->order_by('c.tgl_diajukan', 'DESC');

        return $this->db->get()->result_array();
    }

    // Total anggota tim
public function get_total_anggota_tim($id_atasan) {
    $this->db->from('user u');
    $this->db->join('user_detail ud', 'u.id_user_detail = ud.id_user_detail');
    $this->db->where('u.id_atasan', $id_atasan);
    $this->db->where('u.id_user_level', 1); // Hanya pegawai
    return $this->db->count_all_results();
}

// Statistik umum cuti tim
public function get_statistik_cuti_tim($id_atasan, $tahun) {
    $this->db->select('
        COUNT(*) as total_pengajuan,
        SUM(CASE WHEN c.id_status_cuti = 2 THEN 1 ELSE 0 END) as total_disetujui,
        SUM(CASE WHEN c.id_status_cuti = 3 THEN 1 ELSE 0 END) as total_ditolak,
        SUM(CASE WHEN c.id_status_cuti IN (1,4) THEN 1 ELSE 0 END) as total_pending,
        SUM(DATEDIFF(c.berakhir, c.mulai) + 1) as total_hari_cuti
    ');
    $this->db->from('cuti c');
    $this->db->join('user u', 'c.id_user = u.id_user');
    $this->db->where('u.id_atasan', $id_atasan);
    $this->db->where('YEAR(c.tgl_diajukan)', $tahun);
    $this->db->where_in('c.id_status_cuti', [1,2,3,4]);
    
    return $this->db->get()->row_array();
}

// Data cuti per bulan
public function get_cuti_per_bulan($id_atasan, $tahun) {
    $this->db->select('
        MONTH(c.mulai) as month_num,
        MONTHNAME(c.mulai) as month_name,
        SUM(DATEDIFF(c.berakhir, c.mulai) + 1) as total_days,
        COUNT(*) as total_cuti
    ');
    $this->db->from('cuti c');
    $this->db->join('user u', 'c.id_user = u.id_user');
    $this->db->where('u.id_atasan', $id_atasan);
    $this->db->where('YEAR(c.mulai)', $tahun);
    $this->db->where('c.id_status_cuti', 2); // Hanya yang disetujui
    $this->db->group_by('MONTH(c.mulai), MONTHNAME(c.mulai)');
    $this->db->order_by('month_num');
    
    return $this->db->get()->result_array();
}

// Statistik per jenis cuti
public function get_statistik_jenis_cuti($id_atasan, $tahun) {
    $this->db->select('
        jc.nama_cuti,
        COUNT(*) as total_pengajuan,
        SUM(DATEDIFF(c.berakhir, c.mulai) + 1) as total_hari
    ');
    $this->db->from('cuti c');
    $this->db->join('user u', 'c.id_user = u.id_user');
    $this->db->join('jenis_cuti jc', 'c.id_jenis_cuti = jc.id_jenis_cuti');
    $this->db->where('u.id_atasan', $id_atasan);
    $this->db->where('YEAR(c.tgl_diajukan)', $tahun);
    $this->db->where_in('c.id_status_cuti', [1,2,3,4]);
    $this->db->group_by('jc.nama_cuti');
    $this->db->order_by('total_pengajuan', 'DESC');
    
    return $this->db->get()->result_array();
}
}

