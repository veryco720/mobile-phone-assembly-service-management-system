<?php 
class Supplier_model extends CI_Model
{
    /**
     * Ambil semua data supplier untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per status (aktif/nonaktif)
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('supplier');
        $sql = "SELECT `id_supplier`, `nama_supplier`, `alamat`, `telepon`, `email` 
                FROM supplier
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
     * Ambil data supplier berdasarkan ID (untuk edit)
     * @param int $id - ID supplier
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM supplier WHERE id_supplier='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data supplier baru
     * @param array $data - Data supplier
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi nama_supplier
     * - Validasi format email
     * - Validasi format telepon
     */
    public function insertData($data)
    {
        $query = $this->db->insert('supplier', $data);
        return $query;
    }

    /**
     * Update data supplier
     * @param array $data - Data supplier (dengan id_supplier)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi email & telepon
     * - Cek duplikasi nama
     */
    public function updateData($data)
    {
        $this->db->where('id_supplier', $data['id_supplier']);
        $query = $this->db->update('supplier', $data);
        return array('result' => $query);
    }

    /**
     * Delete data supplier
     * @param array $data (id_supplier)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah supplier memiliki relasi (komponen, dll)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_supplier', $data['id_supplier']);
        $success = $this->db->delete('supplier');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek duplikasi ID supplier
     * @param string $INSTANSI - ID yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Gunakan query builder
     * - Perbaiki menjadi cek nama_supplier (bukan ID)
     */
    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM supplier WHERE id_supplier='$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data supplier (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting
     * - Hanya tampilkan supplier aktif
     */
    public function getAllProduk()
    {
        $query = $this->db->get('supplier');
        return $query->result();
    }
}