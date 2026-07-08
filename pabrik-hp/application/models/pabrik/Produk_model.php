<?php class Produk_model extends CI_Model
{


    public function getDataAll($data)
    {
        $queryall = $this->db->get('produk_hp'); //query builder -> list data
        $sql = "SELECT `id_produk`, `nama_produk`, `tipe`, `warna`, `kapasitas_ram`,`kapasitas_rom` FROM produk_hp
  where " . $data['filtervalue'] . " like '%" . $data['filtertext'] . "%' limit " . $data["start"] . "," . $data['length'];
        $query   = $this->db->query($sql);      //query builder -> list data
        $data    = $query->result();           //eksekusi query
        $total   = $queryall->num_rows();     //jumlah data
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }
    public function getDataId($id)
    {
        $sql = "SELECT * FROM produk_hp WHERE id_produk='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function insertData($data)
    {
        $query = $this->db->insert('produk_hp', $data);
        return $query;
    }
    public function updateData($data)
    {
        // Pastikan menggunakan id_level atau kolom yang sesuai
        $this->db->where('id_produk', $data['id_produk']);
        $query = $this->db->update('produk_hp', $data);
        return array('result' => $query);
    }


    public function deleteData($data)
    {
        $this->db->where('id_produk', $data['id_produk']); // Menggunakan id_level sebagai kondisi
        $success = $this->db->delete('produk_hp');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }
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

    public function getAllProduk()
    {
        $query = $this->db->get('produk_hp');
        return $query->result();
    }
}
    // Model