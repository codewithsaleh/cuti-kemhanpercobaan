<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pesan extends CI_Model {

    private $table = 'tbl_pesan';

    public function get_percakapan($user1_id, $user2_id)
    {
        $this->db->from($this->table);
        $this->db->group_start();
        $this->db->where('id_pengirim', $user1_id);
        $this->db->where('id_penerima', $user2_id);
        $this->db->group_end();
        $this->db->or_group_start();
        $this->db->where('id_pengirim', $user2_id);
        $this->db->where('id_penerima', $user1_id);
        $this->db->group_end();
        $this->db->order_by('waktu_kirim', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function simpan_pesan($pengirim_id, $penerima_id, $isi_pesan)
    {
        $data = array(
            'id_pengirim' => $pengirim_id,
            'id_penerima' => $penerima_id,
            'isi_pesan' => $isi_pesan,
            'waktu_kirim' => date('Y-m-d H:i:s'),
            'dibaca' => 0 // [BARU] Setiap pesan baru statusnya belum dibaca
        );
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function tarik_pesan($id_pesan, $id_pengirim)
    {
        $this->db->where('id_pesan', $id_pesan);
        $pesan = $this->db->get($this->table)->row();

        if (!$pesan || $pesan->id_pengirim != $id_pengirim) {
            return false;
        }
        
        if ($pesan->isi_pesan == 'Pesan ini telah ditarik.') {
            return false;
        }

        $waktu_kirim = strtotime($pesan->waktu_kirim);
        $waktu_sekarang = time();
        $selisih_detik = $waktu_sekarang - $waktu_kirim;

        if ($selisih_detik > 300) {
            return false;
        }

        $data_update = ['isi_pesan' => 'Pesan ini telah ditarik.'];
        $this->db->where('id_pesan', $id_pesan);
        $this->db->update($this->table, $data_update);

        return $this->db->affected_rows() > 0;
    }

    // [FUNGSI BARU] Untuk mendapatkan daftar percakapan admin
    public function get_conversations_admin($admin_id)
    {
        // Query ini akan mengambil daftar pegawai yang pernah berinteraksi,
        // menghitung pesan belum dibaca, dan mengurutkannya berdasarkan pesan terakhir.
        $sql = "
            SELECT
                u.id_user,
                ud.nama_lengkap,
                ud.jabatan,
                MAX(p.waktu_kirim) AS last_message_time,
                SUM(CASE WHEN p.id_penerima = ? AND p.dibaca = 0 THEN 1 ELSE 0 END) AS unread_count
            FROM
                tbl_pesan p
            JOIN
                user u ON (p.id_pengirim = u.id_user OR p.id_penerima = u.id_user)
            JOIN
                user_detail ud ON u.id_user_detail = ud.id_user_detail
            WHERE
                (p.id_pengirim = ? OR p.id_penerima = ?) AND u.id_user != ? AND u.id_user_level = 1
            GROUP BY
                u.id_user, ud.nama_lengkap, ud.jabatan
            ORDER BY
                last_message_time DESC
        ";
        
        $query = $this->db->query($sql, [$admin_id, $admin_id, $admin_id, $admin_id]);
        return $query->result_array();
    }

    // [FUNGSI BARU] Untuk menandai pesan sebagai sudah dibaca
    public function mark_as_read($penerima_id, $pengirim_id)
    {
        $this->db->where('id_penerima', $penerima_id);
        $this->db->where('id_pengirim', $pengirim_id);
        $this->db->where('dibaca', 0);
        $this->db->update($this->table, ['dibaca' => 1]);
    }
}

