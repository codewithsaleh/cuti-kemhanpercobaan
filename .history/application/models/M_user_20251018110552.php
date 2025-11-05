<?php

class M_user extends CI_Model
{

    public function get_all_pegawai()
    {
        $hasil = $this->db->query('
            SELECT 
                user.*, 
                user_detail.*, 
                jenis_kelamin.jenis_kelamin,
                atasan_detail.nama_lengkap as nama_atasan
            FROM user 
            LEFT JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail 
            LEFT JOIN jenis_kelamin ON user_detail.id_jenis_kelamin = jenis_kelamin.id_jenis_kelamin 
            LEFT JOIN user as atasan_user ON user.id_atasan = atasan_user.id_user
            LEFT JOIN user_detail as atasan_detail ON atasan_user.id_user_detail = atasan_detail.id_user_detail
            WHERE user.id_user_level = 1 
            GROUP BY user.id_user
            ORDER BY user_detail.nama_lengkap ASC
        ');
        return $hasil;
    }

    public function get_all_manageable_users()
    {
        $hasil = $this->db->query('
            SELECT 
                user.*, 
                user_detail.*, 
                user_level.user_level,
                atasan_detail.nama_lengkap as nama_atasan
            FROM user 
            LEFT JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail 
            LEFT JOIN user_level ON user.id_user_level = user_level.id_user_level
            LEFT JOIN user as atasan_user ON user.id_atasan = atasan_user.id_user
            LEFT JOIN user_detail as atasan_detail ON atasan_user.id_user_detail = atasan_detail.id_user_detail
            WHERE user.id_user_level IN (1, 3)
            GROUP BY user.id_user
            ORDER BY user.id_user_level ASC, user_detail.nama_lengkap ASC
        ');
        return $hasil;
    }


    public function get_all_atasan()
    {
        $hasil = $this->db->query('
            SELECT 
                user.id_user, 
                user_detail.nama_lengkap 
            FROM user 
            JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail
            WHERE id_user_level = 3 
            ORDER BY user_detail.nama_lengkap ASC
        ');
        return $hasil;
    }

    public function count_all_pegawai()
    {
        // PERBAIKAN: Hitung semua user aktif, bukan hanya pegawai
        $this->db->select('COUNT(*) as total');
        $this->db->from('user');
        $this->db->where('is_active', 1);
        return $this->db->get();
    }

    // Method baru jika ingin memisahkan
    public function count_all_users()
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('user');
        $this->db->where('is_active', 1);
        return $this->db->get();
    }

    public function count_only_pegawai()
    {
        $this->db->select('COUNT(*) as total');
        $this->db->from('user');
        $this->db->where('is_active', 1);
        $this->db->where('id_user_level', 1); // Hanya pegawai
        return $this->db->get();
    }

    public function count_by_level()
    {
        $this->db->select('
            COUNT(*) as total_semua,
            COUNT(CASE WHEN id_user_level = 1 THEN 1 END) as total_pegawai,
            COUNT(CASE WHEN id_user_level = 2 THEN 1 END) as total_admin,
            COUNT(CASE WHEN id_user_level = 3 THEN 1 END) as total_superadmin
        ');
        $this->db->from('user');
        $this->db->where('is_active', 1);
        return $this->db->get();
    }

    public function count_all_admin()
    {
        $hasil = $this->db->query('SELECT COUNT(id_user) as total_user FROM user WHERE id_user_level = 2');
        return $hasil;
    }

    public function get_all_admin()
    {
        $hasil = $this->db->query('SELECT * FROM user WHERE id_user_level = 2');
        return $hasil;
    }

    /**
     * [PERBAIKAN FINAL] Fungsi ini sekarang menggunakan JOIN yang benar dan alias yang aman 
     * untuk mengambil nama atasan.
     */
    public function get_pegawai_by_id($id_user)
    {
        $hasil = $this->db->query("
            SELECT 
                pegawai_user.*, 
                pegawai_user_detail.*,
                atasan_user_detail.nama_lengkap as nama_atasan
            FROM user AS pegawai_user
            JOIN user_detail AS pegawai_user_detail ON pegawai_user.id_user_detail = pegawai_user_detail.id_user_detail 
            LEFT JOIN user AS atasan_user ON pegawai_user.id_atasan = atasan_user.id_user
            LEFT JOIN user_detail AS atasan_user_detail ON atasan_user.id_user_detail = atasan_user_detail.id_user_detail
            WHERE pegawai_user.id_user = '$id_user'
        ");
        return $hasil;
    }
    
    public function cek_login($username)
    {
        $hasil=$this->db->query("SELECT * FROM user JOIN user_detail ON user.id_user_detail = user_detail.id_user_detail WHERE username='$username'");
        return $hasil;
    }

    public function pendaftaran_user($id, $username, $email, $password, $id_user_level)
    {
       $this->db->trans_start();

       $this->db->query("INSERT INTO user(id_user,username,password,email,id_user_level, id_user_detail) VALUES ('$id','$username',md5('$password'),'$email','$id_user_level','$id')");
       $this->db->query("INSERT INTO user_detail(id_user_detail, nama_lengkap) VALUES ('$id', '$username')");

       $this->db->trans_complete();
        if($this->db->trans_status()==true)
            return true;
        else
            return false;
    }

    public function update_user_detail($id, $nama_lengkap, $id_jenis_kelamin, $no_telp, $alamat, $nip, $pangkat, $jabatan)
    {
       $this->db->trans_start();
       
       $this->db->query("UPDATE user_detail SET nama_lengkap='$nama_lengkap', id_jenis_kelamin='$id_jenis_kelamin' ,no_telp='$no_telp', alamat='$alamat', nip='$nip', pangkat='$pangkat', jabatan='$jabatan' WHERE id_user_detail='$id'");

       $this->db->trans_complete();
        if($this->db->trans_status()==true)
            return true;
        else
            return false;
    }

    public function insert_pegawai($id, $username, $email, $password, $id_user_level, $nama_lengkap, $id_jenis_kelamin, $no_telp, $alamat, $nip, $pangkat, $jabatan, $id_atasan)
    {
       $this->db->trans_start();

       $this->db->query("INSERT INTO user(id_user, username, password, email, id_user_level, id_user_detail, id_atasan) VALUES ('$id','$username',md5('$password'),'$email','$id_user_level','$id', '$id_atasan')");
       
       $this->db->query("INSERT INTO user_detail(id_user_detail, nama_lengkap, id_jenis_kelamin, no_telp, alamat, nip, pangkat, jabatan, jatah_cuti) VALUES ('$id','$nama_lengkap','$id_jenis_kelamin','$no_telp','$alamat', '$nip', '$pangkat', '$jabatan', 12)");

       $this->db->trans_complete();
        if($this->db->trans_status()==true)
            return true;
        else
            return false;
    }

    public function update_pegawai($id, $username, $email, $password, $id_user_level, $nama_lengkap, $id_jenis_kelamin, $no_telp, $alamat, $nip, $pangkat, $jabatan, $id_atasan)
    {
        $this->db->trans_start();

        // --- UPDATE TABEL USER ---
        $user_data = [
            'username' => $username,
            'email' => $email
        ];

        if (!empty($password)) {
            $user_data['password'] = md5($password);
        }
        
        if ($id_user_level == 1) { // Hanya update atasan jika yang diedit adalah Pegawai
            $user_data['id_atasan'] = $id_atasan;
        }

        $this->db->where('id_user', $id);
        $this->db->update('user', $user_data);

        // --- UPDATE TABEL USER_DETAIL ---
        $user_detail_data = [
            'nama_lengkap' => $nama_lengkap,
            'nip' => $nip,
            'id_jenis_kelamin' => $id_jenis_kelamin,
            'no_telp' => $no_telp,
            'alamat' => $alamat
        ];

        if ($id_user_level == 1) { // Hanya update pangkat dan jabatan jika yang diedit adalah Pegawai
            $user_detail_data['pangkat'] = $pangkat;
            $user_detail_data['jabatan'] = $jabatan;
        }

        $this->db->where('id_user_detail', $id);
        $this->db->update('user_detail', $user_detail_data);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete_pegawai($id)
    {
       $this->db->trans_start();
       $this->db->query("DELETE FROM user WHERE id_user='$id'");
       $this->db->query("DELETE FROM user_detail WHERE id_user_detail='$id'");
       $this->db->trans_complete();
        if($this->db->trans_status()==true)
            return true;
        else
            return false;
    }

    public function update_user($id, $username, $password) { $this->db->trans_start(); if (!empty($password)) { $this->db->query("UPDATE user SET username='$username', password=md5('$password') WHERE id_user='$id'"); } else { $this->db->query("UPDATE user SET username='$username' WHERE id_user='$id'"); } $this->db->trans_complete(); if ($this->db->trans_status() == true) return true; else return false; }
    public function delete_admin($id) { $this->db->trans_start(); $this->db->query("DELETE FROM user WHERE id_user='$id'"); $this->db->query("DELETE FROM user_detail WHERE id_user_detail='$id'"); $this->db->trans_complete(); if ($this->db->trans_status() == true) return true; else return false; }
    public function reset_password_by_id($id_user) { $this->db->trans_start(); $default_password = 'kemhan2025'; $this->db->query("UPDATE user SET password=md5('$default_password') WHERE id_user='$id_user'"); $this->db->trans_complete(); return $this->db->trans_status(); }
    public function update_password_only($id, $password) { $this->db->trans_start(); if (!empty($password)) { $this->db->query("UPDATE user SET password=md5('$password') WHERE id_user='$id'"); } $this->db->trans_complete(); if ($this->db->trans_status() == true) return true; else return false; }
    public function get_all_pegawai_with_sisa_cuti() { $this->db->select('user_detail.nama_lengkap, user_detail.nip, user_detail.jatah_cuti'); $this->db->from('user'); $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail'); $this->db->where('user.id_user_level', 1); $this->db->order_by('user_detail.nama_lengkap', 'ASC'); return $this->db->get()->result_array(); }
    public function get_admin_for_chat() { $this->db->select('user.id_user, user_detail.nama_lengkap'); $this->db->from('user'); $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail'); $this->db->where('user.id_user_level', 2); $this->db->limit(1); return $this->db->get()->row(); }
    public function get_user_by_email($email) { return $this->db->get_where('user', ['email' => $email])->row_array(); }
    public function save_reset_token($user_id, $token) { $data = [ 'reset_token' => $token, 'reset_token_created_at' => date('Y-m-d H:i:s') ]; $this->db->where('id_user', $user_id); return $this->db->update('user', $data); }
    public function get_user_by_reset_token($token) { return $this->db->get_where('user', ['reset_token' => $token])->row_array(); }
    public function reset_password($user_id, $new_password) { $this->db->trans_start(); $this->db->where('id_user', $user_id); $this->db->update('user', ['password' => md5($new_password)]); $this->db->where('id_user', $user_id); $this->db->update('user', ['reset_token' => null, 'reset_token_created_at' => null]); $this->db->trans_complete(); return $this->db->trans_status(); }
    public function count_bawahan_by_atasan($id_atasan) { $hasil = $this->db->query(" SELECT COUNT(id_user) as total_user FROM user WHERE id_atasan = '$id_atasan' "); return $hasil; }
    public function get_all_bawahan($id_atasan) { $this->db->select('user.*, user_detail.*'); $this->db->from('user'); $this->db->join('user_detail', 'user.id_user_detail = user_detail.id_user_detail'); $this->db->where('user.id_atasan', $id_atasan); $this->db->order_by('user_detail.nama_lengkap', 'ASC'); return $this->db->get(); }
    public function get_user_by_id($id_user){ return $this->get_pegawai_by_id($id_user); }
}

