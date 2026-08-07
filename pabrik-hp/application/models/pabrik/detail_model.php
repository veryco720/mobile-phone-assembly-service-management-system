<?php
class detail_model extends CI_Model
{
    /**
     * Ambil semua data detail produksi untuk DataTable (server-side)
     * @param array $data (filtervalue, filtertext, start, length)
     * @return array (RecordsTotal, RecordsFiltered, Data)
     * 
     * @development: 
     * - Tambah sorting & pagination
     * - Gunakan query builder
     * - Hitung total filtered
     */
    public function getDataAll($data)
    {
        $queryall = $this->db->get('detail_produksi');

        // Mapping kolom filter agar tidak ambigu
        $filter = $data['filtervalue'];

        switch ($filter) {
            case 'id_produksi':
                $filter = 'd.id_produksi';
                break;

            case 'nama_komponen':
                $filter = 'k.nama_komponen';
                break;

            default:
                $filter = 'd.id_produksi';
                break;
        }

        $sql = "SELECT
                    d.id_detail,
                    CONCAT(d.id_produksi, ' - ', ph.nama_produk) AS id_produksi,
                    d.id_komponen,
                    d.jumlah,
                    k.nama_komponen
                FROM detail_produksi d
                LEFT JOIN komponen k
                    ON d.id_komponen = k.id_komponen
                LEFT JOIN produksi p
                    ON d.id_produksi = p.id_produksi
                LEFT JOIN produk_hp ph
                    ON p.id_produk = ph.id_produk
                WHERE $filter LIKE '%" . $data['filtertext'] . "%'
                LIMIT " . $data['start'] . "," . $data['length'];

        $query = $this->db->query($sql);

        $result = $query->result();
        $total  = $queryall->num_rows();

        return array(
            "RecordsTotal"      => $total,
            "RecordsFiltered"   => $total,
            "Data"              => $result
        );
    }

    /**
     * Ambil detail produksi berdasarkan ID (untuk edit)
     * @param int $id - ID detail
     * @return object
     * 
     * @development: 
     * - Gunakan query builder
     * - Tambah validasi jika data tidak ditemukan
     */
    public function getDataId($id)
    {
        $sql   = "SELECT * FROM detail_produksi WHERE id_detail = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    /**
     * Insert data detail produksi baru
     * @param array $data - Data detail
     * @return boolean
     * 
     * @development: 
     * - Cek duplikasi data
     * - Validasi jumlah > 0
     */
    public function insertData($data)
    {
        $query = $this->db->insert('detail_produksi', $data);
        return $query;
    }

    /**
     * Update data detail produksi
     * @param array $data - Data detail (dengan id_detail)
     * @return array (result)
     * 
     * @development: 
     * - Cek apakah data ada
     * - Validasi data sebelum update
     */
    public function updateData($data)
    {
        $this->db->where('id_detail', $data['id_detail']);
        $query = $this->db->update('detail_produksi', $data);
        return array('result' => $query);
    }

    /**
     * Delete data detail produksi
     * @param array $data (id_detail)
     * @return array (result, message)
     * 
     * @development: 
     * - Cek apakah data memiliki relasi
     * - Soft delete lebih aman
     */
    public function deleteData($data)
    {
        $this->db->where('id_detail', $data['id_detail']);
        $success = $this->db->delete('detail_produksi');
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
     * - Cek field yang benar
     */
    public function checkNama($INSTANSI)
    {
        $sql   = "SELECT * FROM detail_produksi WHERE detail_produksi = '$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    /**
     * Ambil semua data detail produksi (tanpa filter)
     * @return object
     * 
     * @development: 
     * - Tambah join dengan tabel terkait
     * - Tambah sorting
     */
    public function getAllDetail()
    {
        $query = $this->db->get('detail_produksi');
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
    public function getProduksi()
    {
        $sql = "SELECT
                    dp.id_produksi,
                    ph.nama_produk
                FROM detail_produksi dp
                LEFT JOIN produksi p
                    ON dp.id_produksi = p.id_produksi
                LEFT JOIN produk_hp ph
                    ON p.id_produk = ph.id_produk";

        return $this->db->query($sql)->result();
    }

    
}