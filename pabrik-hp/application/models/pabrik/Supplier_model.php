<?php class Supplier_model extends CI_Model
{


    public function getDataAll($data)
    {
        $queryall = $this->db->get('supplier'); //query builder -> list data
        $sql = "SELECT `id_supplier`, `nama_supplier`, `alamat`, `telepon`, `email` FROM supplier
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
        $sql = "SELECT * FROM supplier WHERE id_supplier='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function insertData($data)
    {
        $query = $this->db->insert('supplier', $data);
        return $query;
    }
    public function updateData($data)
    {
        // Pastikan menggunakan id_level atau kolom yang sesuai
        $this->db->where('id_supplier', $data['id_supplier']);
        $query = $this->db->update('supplier', $data);
        return array('result' => $query);
    }


    public function deleteData($data)
    {
        $this->db->where('id_supplier', $data['id_supplier']); // Menggunakan id_level sebagai kondisi
        $success = $this->db->delete('supplier');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }
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

    public function getAllProduk()
    {
        $query = $this->db->get('supplier'); //query builder -> list data
        return $query->result();
    }
}
    // Model