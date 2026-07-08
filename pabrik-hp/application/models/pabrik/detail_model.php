<?php
class detail_model extends CI_Model
{
    public function getDataAll($data)
    {
        $queryall = $this->db->get('detail_produksi');
        $sql = "SELECT d.`id_detail`, d.`id_produksi`, d.`id_komponen`,d.`jumlah`,
        k.`nama_komponen`
         FROM detail_produksi AS d 
         LEFT JOIN komponen AS k ON d.id_komponen = k.id_komponen
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
        $sql   = "SELECT * FROM detail_produksi WHERE id_detail = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insertData($data)
    {
        $query = $this->db->insert('detail_produksi', $data);
        return $query;
    }

    public function updateData($data)
    {
        $this->db->where('id_detail', $data['id_detail']);
        $query = $this->db->update('detail_produksi', $data);
        return array('result' => $query);
    }

    public function deleteData($data)
    {
        $this->db->where('id_detail', $data['id_detail']);
        $success = $this->db->delete('detail_produksi');
        return array(
            'result'  => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data, memiliki relasi.'
        );
    }

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

    public function getAllDetail()
    {
        $query = $this->db->get('detail_produksi');
        return $query->result();
    }
}