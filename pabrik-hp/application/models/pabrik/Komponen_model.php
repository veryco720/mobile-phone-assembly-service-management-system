<?php class Komponen_model extends CI_Model
{


    public function getDataAll($data)
    {
        $queryall = $this->db->get('komponen'); //query builder -> list data
        $sql = "SELECT k.`id_komponen`,
                        k.`id_supplier`,
                        k.`nama_komponen`,
                        k.`kategori`,
                        k.`stok`,
                        k.`satuan`,
                        k.`harga`,

                        s.`nama_supplier`AS nama_supplier

                        FROM komponen AS k
                        LEFT JOIN supplier AS s ON k.id_supplier = s.id_supplier
                        where " . $data['filtervalue'] . " like '%" . $data['filtertext'] . "%' limit " . $data["start"] . "," . $data['length'];
        $query = $this->db->query($sql);        //query builder -> list data
        $data = $query->result();               //eksekusi query
        $total = $queryall->num_rows();         //jumlah data
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }
    public function getDataId($id)
    {
        $sql = "SELECT * FROM komponen WHERE id='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function insertData($data)
    {
        $query = $this->db->insert('komponen', $data);
        return $query;
    }
    public function updateData($data)
    {
        // Pastikan menggunakan id_level atau kolom yang sesuai
        $this->db->where('id_komponen', $data['id_komponen']); // Menggunakan id_level sebagai kondisi
        $query = $this->db->update('komponen', $data);
        return array('result' => $query);
    }


    public function deleteData($data)
    {
        $this->db->where('id_komponen', $data['id_komponen']); // Menggunakan id_level sebagai kondisi
        $success = $this->db->delete('komponen');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }



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

    public function getAllKomponen()
    {
        $query = $this->db->get('komponen');
        return $query->result();
    }
}
    // Komponen_model