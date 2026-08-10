<?php
class gudang_model extends CI_Model
{
    /**
     * Ambil semua data gudang untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per produk/lokasi
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('gudang');
        $sql = "SELECT g.`id_gudang`, g.`id_produk`, g.`stok_produk`, g.`lokasi`, g.`tanggal_update`,
        ph.`nama_produk`
         FROM gudang AS g
         LEFT JOIN produk_hp AS ph ON g.id_produk = ph.id_produk
         WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%'
        LIMIT " . $data["start"] . "," . $data['length'];

        $query      = $this->db->query($sql);
        $result     = $query->result();
        $total      = $queryall->num_rows();
        $dataRecord = array(
            "RecordsTotal"    => $total,
            "RecordsFiltered" => $total,
            "Data"            => $result,
        );
        return $dataRecord;
    }

    /**
     * Ambil data gudang berdasarkan ID (untuk edit)
     * @param int $id - ID gudang
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql   = "SELECT * FROM gudang WHERE id_gudang = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data gudang baru
     * @param array $data - Data gudang
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi produk di lokasi
     * - Validasi stok >= 0
     * - Otomatis isi tanggal_update
     */
    public function insertData($data)
    {
        $query = $this->db->insert('gudang', $data);
        return $query;
    }

    /**
     * Update data gudang
     * @param array $data - Data gudang (dengan id_gudang)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi stok >= 0
     * - Otomatis update tanggal_update
     */
    public function updateData($data)
    {
        $this->db->where('id_gudang', $data['id_gudang']);
        $query = $this->db->update('gudang', $data);
        return array('result' => $query);
    }

    /**
     * Delete data gudang
     * @param array $data (id_gudang)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah data memiliki relasi
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_gudang', $data['id_gudang']);
        $success = $this->db->delete('gudang');
        return array(
            'result'  => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data, memiliki relasi.'
        );
    }

    /**
     * Cek apakah nama sudah ada (validasi duplikat)
     * @param string $INSTANSI - Nama yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Fungsi ini perlu diperbaiki logikanya
     * - Perbaiki field yang digunakan
     */
    public function checkNama($INSTANSI)
    {
        $sql   = "SELECT * FROM gudang WHERE gudang = '$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data gudang (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan produk
     * - Tambah sorting
     */
    public function getAllGudang()
    {
        $query = $this->db->get('gudang');
        return $query->result();
    }
}