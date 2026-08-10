<?php 
class Quality_model extends CI_Model
{
    /**
     * Ambil semua data quality control untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per hasil_qc (Lulus/Tidak Lulus)
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('quality_control');
        $sql = "SELECT qc.`id_qc`,
                        qc.`id_produksi`,
                        qc.`id_karyawan`,
                        qc.`hasil_qc`,
                        qc.`catatan`,
                        qc.`tanggal_qc`,
                        k.`nama_karyawan` AS nama_karyawan
                FROM quality_control AS qc
                LEFT JOIN karyawan AS k ON qc.id_karyawan = k.id_karyawan
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
     * Ambil data QC berdasarkan ID (untuk edit)
     * @param int $id - ID QC
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM quality_control WHERE id_qc='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data QC baru
     * @param array $data - Data QC
     * @return boolean
     * 
     * @development: 
     * - Validasi hasil_qc (Lulus/Tidak Lulus)
     * - Cek apakah produksi sudah di-QC
     * - Otomatis set tanggal_qc
     */
    public function insertData($data)
    {
        $query = $this->db->insert('quality_control', $data);
        return $query;
    }

    /**
     * Update data QC
     * @param array $data - Data QC (dengan id_qc)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi hasil_qc
     */
   public function updateData($data)
{
    $id_qc = $data['id_qc'];

    // Jangan ikut mengupdate primary key
    unset($data['id_qc']);

    $this->db->where('id_qc', $id_qc);

    $query = $this->db->update('quality_control', $data);

    return array(
        'result' => $query,
        'message' => $query ? 'Data berhasil diupdate.' : 'Data gagal diupdate.'
    );
}
    /**
     * Delete data QC
     * @param array $data (id_qc)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah QC memiliki relasi
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_qc', $data['id_qc']);
        $success = $this->db->delete('quality_control');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek duplikasi ID (fungsi perlu diperbaiki)
     * @param string $INSTANSI - ID yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Perbaiki variabel $id tidak terdefinisi
     * - Gunakan query builder
     */
    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM quality_control WHERE id_qc='$id'";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data QC (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan produksi & karyawan
     * - Tambah sorting
     */
    public function getAllQuality()
    {
        $query = $this->db->get('quality_control');
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