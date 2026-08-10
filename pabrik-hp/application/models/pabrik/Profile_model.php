<?php

class Profile_model extends CI_Model 
{
    /**
     * Ambil data user berdasarkan ID
     * @param int $id - ID user
     * @return object|null
     * 
     * @development: 
     * - Tambah field yang dibutuhkan (email, no_hp, dll)
     * - Gunakan cache untuk performance
     */
    public function getUser($id)
    {
        return $this->db->where('id_user', $id)->get('users')->row();
    }

    /**
     * Update password user
     * @param int $id - ID user
     * @param string $password - Password baru (plain text)
     * @return boolean
     * 
     * @development: 
     * - Hash password dengan password_hash()
     * - Log aktivitas perubahan password
     * - Kirim email notifikasi
     */
    public function updatePassword($id, $password)
    {
        $this->db->where('id_user', $id);
        return $this->db->update('users', ['password' => $password]);
    }
}