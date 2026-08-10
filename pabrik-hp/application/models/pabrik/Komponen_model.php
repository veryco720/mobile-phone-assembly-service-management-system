<?php 
class Komponen_model extends CI_Model
{
    /**
     * Ambil semua data komponen untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per kategori/supplier
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('komponen');
        $sql = "SELECT k.`id_komponen`,
                        k.`id_supplier`,
                        k.`nama_komponen`,
                        k.`kategori`,
                        k.`stok`,
                        k.`satuan`,
                        k.`harga`,
                        s.`nama_supplier` AS nama_supplier
                FROM komponen AS k
                LEFT JOIN supplier AS s ON k.id_supplier = s.id_supplier
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
     * Ambil data komponen berdasarkan ID (untuk edit)
     * @param int $id - ID komponen
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM komponen WHERE id_komponen='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data komponen baru
     * @param array $data - Data komponen
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi nama_komponen
     * - Validasi stok >= 0
     * - Validasi harga >= 0
     */
    public function insertData($data)
    {
        $query = $this->db->insert('komponen', $data);
        return $query;
    }

    /**
     * Update data komponen
     * @param array $data - Data komponen (dengan id_komponen)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi stok & harga
     * - Cek duplikasi nama
     */
    public function updateData($data)
    {
        $this->db->where('id_komponen', $data['id_komponen']);
        $query = $this->db->update('komponen', $data);
        return array('result' => $query);
    }

    /**
     * Delete data komponen
     * @param array $data (id_komponen)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah komponen memiliki relasi (detail_produksi, dll)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_komponen', $data['id_komponen']);
        $success = $this->db->delete('komponen');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek duplikasi nama komponen
     * @param string $INSTANSI - Nama komponen yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah parameter untuk exclude ID saat update
     */
    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM komponen WHERE nama_komponen='$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data komponen (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan supplier
     * - Tambah sorting
     * - Tambah kondisi stok > 0 (opsional)
     */
    public function getAllKomponen()
    {
        $query = $this->db->get('komponen');
        return $query->result();
    }
}