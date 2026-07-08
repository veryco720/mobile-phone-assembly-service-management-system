<?php
class gudang_model extends CI_Model
{
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

    public function getDataId($id)
    {
        $sql   = "SELECT * FROM gudang WHERE id_gudang = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insertData($data)
    {
        $query = $this->db->insert('gudang', $data);
        return $query;
    }

    public function updateData($data)
    {
        $this->db->where('id_gudang', $data['id_gudang']);
        $query = $this->db->update('gudang', $data);
        return array('result' => $query);
    }

    public function deleteData($data)
    {
        $this->db->where('id_gudang', $data['id_gudang']);
        $success = $this->db->delete('gudang');
        return array(
            'result'  => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data, memiliki relasi.'
        );
    }

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

    public function getAllGudang()
    {
        $query = $this->db->get('gudang');
        return $query->result();
    }
}