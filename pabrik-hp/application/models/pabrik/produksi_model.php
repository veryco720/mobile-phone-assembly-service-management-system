<?php
class produksi_model extends CI_Model
{
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

    public function getDataId($id)
    {
        $sql   = "SELECT * FROM produksi WHERE id_produksi = '$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insertData($data)
    {
        $query = $this->db->insert('produksi', $data);
        return $query;
    }

    public function updateData($data)
    {
        $this->db->where('id_produksi', $data['id_produksi']);
        $query = $this->db->update('produksi', $data);
        return array('result' => $query);
    }

    public function deleteData($data)
    {
        $this->db->where('id_produksi', $data['id_produksi']);
        $success = $this->db->delete('produksi');
        return array(
            'result'  => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data, memiliki relasi.'
        );
    }

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

    public function getAllProduksi()
    {
        $query = $this->db->get('produksi');
        return $query->result();
    }
}