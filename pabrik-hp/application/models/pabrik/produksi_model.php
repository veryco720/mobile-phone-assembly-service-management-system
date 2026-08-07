<?php
class produksi_model extends CI_Model
{
    /**
     * Ambil semua data produksi untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Hitung total filtered
     * - Tambah filter per status/tanggal
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('produksi');
        $sql = "SELECT p.`id_produksi`, p.`id_produk`, p.`id_karyawan`, p.`tanggal_produksi`,
        p.`target`, p.`jumlah_selesai`, p.`status`,
        ph.`nama_produk`, k.`nama_karyawan`
         FROM produksi AS p
         LEFT JOIN produk_hp AS ph ON p.id_produk = ph.id_produk
         LEFT JOIN karyawan AS k ON p.id_karyawan = k.id_karyawan
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
     * Ambil data produksi berdasarkan ID (untuk edit)
     * @param int $id - ID produksi
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql   = "SELECT * FROM produksi WHERE id_produksi = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data produksi baru
     * @param array $data - Data produksi
     * @return boolean
     * 
     * @development: 
     * - Validasi target > 0
     * - Cek ketersediaan produk & karyawan
     * - Otomatis set tanggal_produksi
     */
    public function insertData($data)
    {
        $query = $this->db->insert('produksi', $data);
        return $query;
    }

    /**
     * Update data produksi
     * @param array $data - Data produksi (dengan id_produksi)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi target > 0
     * - Validasi jumlah_selesai <= target
     */
    public function updateData($data)
    {
        $this->db->where('id_produksi', $data['id_produksi']);
        $query = $this->db->update('produksi', $data);
        return array('result' => $query);
    }

    /**
     * Delete data produksi
     * @param array $data (id_produksi)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah produksi memiliki relasi (detail_produksi, qc)
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_produksi', $data['id_produksi']);
        $success = $this->db->delete('produksi');
        return array(
            'result'  => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data, memiliki relasi.'
        );
    }

    /**
     * Cek duplikasi (fungsi perlu diperbaiki)
     * @param string $INSTANSI - Nilai yang dicek
     * @return string "Data Sama" / "OK"
     * 
     * @development: 
     * - Perbaiki logika SQL (field 'produksi' tidak ada)
     * - Gunakan query builder
     */
    public function checkNama($INSTANSI)
    {
        $sql   = "SELECT * FROM produksi WHERE produksi = '$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data produksi (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan produk & karyawan
     * - Tambah sorting
     */
    public function getAllProduksi()
    {
        $query = $this->db->get('produksi');
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