<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Akses_model extends CI_Model 
{
    /**
     * Ambil data role dengan jumlah user (DataTable server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting, pagination yang lebih baik
     * - Gunakan query builder untuk keamanan
     */
    public function getDataAll($data)
    {
        $sql = "SELECT role,
                COUNT(*) as jumlah_user
                FROM users
                WHERE ".$data['filtervalue']."
                LIKE '%".$data['filtertext']."%'
                GROUP BY role
                ORDER BY role ASC
                LIMIT ".$data['start'].", ".$data['length'];

        $query = $this->db->query($sql);
        $result = $query->result();

        return array(
            "RecordsTotal" => count($result),
            "RecordsFiltered" => count($result),
            "Data" => $result
        );
    }

    /**
     * Ambil data role berdasarkan role (untuk select/edit)
     * @param string $role
     * @return object
     * 
     * @development: 
     * - Gunakan query builder untuk keamanan
     */
    public function getDataId($role)
    {
        $sql = "SELECT DISTINCT role
                FROM users
                WHERE role='$role'";

        return $this->db->query($sql)->result();
    }

    /**
     * Ambil semua modul dengan hak akses berdasarkan role
     * @param string $role
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah cache untuk performance
     */
    public function getAksesbyRole($role)
    {
        $sql = "
            SELECT
                m.id_modul,
                m.nama_modul,
                IFNULL(p.can_view,0) as can_view,
                IFNULL(p.can_insert,0) as can_insert,
                IFNULL(p.can_update,0) as can_update,
                IFNULL(p.can_delete,0) as can_delete
            FROM tb_modul m
            LEFT JOIN tb_akses p
            ON p.id_modul = m.id_modul
            AND p.role='$role'
            ORDER BY m.id_modul ASC
        ";

        return $this->db->query($sql)->result();
    }

    /**
     * Update hak akses (hapus lama + insert baru)
     * @param array $data (role, akses)
     * @return array (result)
     * 
     * @development: 
     * - Gunakan transaction untuk atomic operation
     * - Log perubahan akses
     * - Validasi role dan modul
     */
    public function updateData($data)
    {
        $role = $data['role'];
        $akses = $data['akses'];

        // Hapus akses lama
        $this->db->where('role', $role);
        $this->db->delete('tb_akses');

        // Insert akses baru
        foreach($akses as $row) {
            $insert = array(
                'role'        => $role,
                'id_modul'    => $row['id_modul'],
                'can_view'    => !empty($row['can_view']) ? 1 : 0,
                'can_insert'  => !empty($row['can_insert']) ? 1 : 0,
                'can_update'  => !empty($row['can_update']) ? 1 : 0,
                'can_delete'  => !empty($row['can_delete']) ? 1 : 0
            );
            $this->db->insert('tb_akses', $insert);
        }

        return array('result' => true);
    }
}

/* End of file Akses_model.php */