<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_settings extends CI_Model
{

    /**
     * Mengambil nilai dari sebuah pengaturan berdasarkan namanya.
     * @param string $setting_name Nama pengaturan yang ingin diambil.
     * @return string|null Nilai dari pengaturan, atau null jika tidak ditemukan.
     */
    public function get_setting($key)
    {
        $query = $this->db->get_where('settings', ['key' => $key]); // 'key' bukan 'setting_key'
        if ($query->num_rows() > 0) {
            return $query->row()->value; // 'value' bukan 'setting_value'
        }
        return null;
    }


    /**
     * Memperbarui nilai dari sebuah pengaturan.
     * @param string $setting_name Nama pengaturan yang akan diperbarui.
     * @param string $setting_value Nilai baru untuk pengaturan tersebut.
     * @return bool TRUE jika berhasil, FALSE jika gagal.
     */
    public function update_setting($key, $value)
    {
        // Cek apakah setting sudah ada
        $existing = $this->db->get_where('settings', ['key' => $key])->row(); // 'key' bukan 'setting_key'

        if ($existing) {
            // Update existing
            $this->db->where('key', $key); // 'key' bukan 'setting_key'
            return $this->db->update('settings', [
                'value' => $value, // 'value' bukan 'setting_value'
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            // Insert new
            return $this->db->insert('settings', [
                'key' => $key, // 'key' bukan 'setting_key'
                'value' => $value, // 'value' bukan 'setting_value'
                'description' => 'Auto-generated setting', // Tambah description
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }
}
