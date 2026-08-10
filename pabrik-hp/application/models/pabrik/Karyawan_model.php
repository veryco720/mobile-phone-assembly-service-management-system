<?php 
class Karyawan_model extends CI_Model
{
    /**
     * Ambil semua data karyawan untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per status/jabatan
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('karyawan');
        $sql = "SELECT  `id_karyawan`, 
                        `nama_karyawan`,     
                        `jabatan`, 
                        `no_hp`,
                        `alamat`,
                        `status`
                FROM karyawan 
                WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%' 
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
     * Ambil data karyawan berdasarkan ID (untuk edit)
     * @param int $id - ID karyawan
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM karyawan WHERE id_karyawan='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data karyawan baru
     * @param array $data - Data karyawan
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi nama/no_hp
     * - Validasi format no_hp
     * - Validasi email jika ada
     */
    public function insertData($data)
    {
        $query = $this->db->insert('karyawan', $data);
        return $query;
    }

    /**
     * Update data karyawan
     * @param array $data - Data karyawan (dengan id_karyawan)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi data sebelum update
     */
    public function updateData($data)
    {
        $this->db->where('id_karyawan', $data['id_karyawan']);
        $query = $this->db->update('karyawan', $data);
        return array('result' => $query);
    }

    /**
     * Delete data karyawan
     * @param array $data (id_karyawan)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah karyawan memiliki relasi (user, produksi, dll)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_karyawan', $data['id_karyawan']);
        $success = $this->db->delete('karyawan');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek apakah ID sudah ada (validasi duplikat)
     * @param string $INSTANSI - ID yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Fungsi ini perlu diperbaiki logikanya
     * - Perbaiki field yang digunakan
     */
    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM karyawan WHERE karyawan='$INSTANSI' ";
        $query = $this->db->query($sql); 
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data karyawan (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting (nama, status)
     * - Hanya tampilkan karyawan aktif
     */
    public function getAllKaryawan()
    {
        $query = $this->db->get('karyawan');
        return $query->result();
    }
}