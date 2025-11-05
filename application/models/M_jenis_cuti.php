<?php

class M_jenis_cuti extends CI_Model
{
    public function get_all_jenis_cuti()
    {
        return $this->db->get('jenis_cuti');
    }
}
