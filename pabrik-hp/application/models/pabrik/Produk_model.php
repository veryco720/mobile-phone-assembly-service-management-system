<?php 
class Produk_model extends CI_Model
{
    /**
     * Ambil semua data produk untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per tipe/warna
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('produk_hp');
        $sql = "SELECT `id_produk`, `nama_produk`, `tipe`, `warna`, `kapasitas_ram`, `kapasitas_rom` 
                FROM produk_hp
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
     * Ambil data produk berdasarkan ID (untuk edit)
     * @param int $id - ID produk
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql = "SELECT * FROM produk_hp WHERE id_produk='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data produk baru
     * @param array $data - Data produk
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi nama_produk
     * - Validasi format RAM/ROM
     */
    public function insertData($data)
    {
        $query = $this->db->insert('produk_hp', $data);
        return $query;
    }

    /**
     * Update data produk
     * @param array $data - Data produk (dengan id_produk)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi format RAM/ROM
     * - Cek duplikasi nama
     */
    public function updateData($data)
    {
        $this->db->where('id_produk', $data['id_produk']);
        $query = $this->db->update('produk_hp', $data);
        return array('result' => $query);
    }

    /**
     * Delete data produk
     * @param array $data (id_produk)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah produk memiliki relasi (gudang, produksi, dll)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_produk', $data['id_produk']);
        $success = $this->db->delete('produk_hp');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }

    /**
     * Cek duplikasi ID produk
     * @param string $INSTANSI - ID yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Gunakan query builder
     * - Perbaiki field yang digunakan (seharusnya cek nama_produk)
     */
    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM produk_hp WHERE id_produk='$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data produk (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah sorting
     * - Hanya tampilkan produk yang memiliki stok
     */
    public function getAllProduk()
    {
        $query = $this->db->get('produk_hp');
        return $query->result();
    }
}