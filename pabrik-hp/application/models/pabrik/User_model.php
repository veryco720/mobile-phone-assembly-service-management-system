<?php 
class User_model extends CI_Model
{
    /**
     * Ambil semua data user untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per role/status
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('users');
        $sql = "SELECT 
                tb1.id_user,
                tb1.id_karyawan,
                tb1.username,
                tb1.password,
                tb1.role,
                tb1.status,
                k.nama_karyawan as nama_karyawan
                FROM users AS tb1
                LEFT JOIN karyawan AS k ON tb1.id_karyawan = k.id_karyawan
                WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%'
                ORDER BY tb1.id_user ASC
                LIMIT " . $data["start"] . "," . $data['length'];
        
        $query = $this->db->query($sql);
        $data = $query->result();
        $total = $queryall->num_rows();
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }

    /**
     * Ambil data user berdasarkan ID (untuk edit)
     * @param int $id - ID user
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM users WHERE id_user='$id'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data user baru
     * @param array $data - Data user
     * @return boolean
     * 
     * @development: 
     * - Hash password dengan password_hash()
     * - Cek duplikasi username
     * - Validasi role
     */
    public function insertData($data)
    {
        $query = $this->db->insert('users', $data);
        return $query;
    }

    /**
     * Update data user
     * @param array $data - Data user (dengan id_user)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Hash password jika diubah
     * - Cek duplikasi username
     */
    public function updateData($data)
    {
        $this->db->where('id_user', $data['id_user']);
        $query = $this->db->update('users', $data);
        return array('result' => $query);
    }

    /**
     * Delete data user
     * @param array $data (id_user)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah user memiliki relasi
     * - Soft delete lebih aman
     * - Jangan hapus user yang sedang login
     */
    public function deleteData($data)
    {
        $this->db->where('id_user', $data['id_user']);
        $success = $this->db->delete('users');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data.'
        );
    }

    /**
     * Cek duplikasi ID user
     * @param int $id - ID yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Gunakan query builder
     * - Perbaiki menjadi cek username (bukan ID)
     */
    public function checkId($id)
    {
        $sql = "SELECT * FROM users WHERE id_user='$id'";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data user (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan karyawan
     * - Tambah sorting
     * - Hide password untuk keamanan
     */
    public function getAllUser()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Ambil data karyawan beserta role untuk dropdown
     * @return object
     * 
     * @development: 
     * - Tambah filter role QC
     * - Tambah sorting nama karyawan
     */
    public function getKaryawanDanRole()
    {
        $sql = "SELECT
                    k.id_karyawan,
                    k.nama_karyawan,
                    u.role
                FROM karyawan k
                LEFT JOIN users u
                    ON k.id_karyawan = u.id_karyawan";

        return $this->db->query($sql)->result();
    }
}