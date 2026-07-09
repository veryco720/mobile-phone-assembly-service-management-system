<?php class Quality_model extends CI_Model
{


    public function getDataAll($data)
    {
        $queryall = $this->db->get('quality_control'); //query builder -> list data
        $sql = "SELECT qc.`id_qc`,
                        qc.`id_produksi`,
                        qc.`id_karyawan`,
                        qc.`hasil_qc`,
                        qc.`catatan`,
                        qc.`tanggal_qc`,

                        k.`nama_karyawan`AS nama_karyawan

                        FROM quality_control AS qc
                        LEFT JOIN karyawan AS k ON qc.id_karyawan = k.id_karyawan


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
        $sql = "SELECT * FROM quality_control WHERE id='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    }
    public function insertData($data)
    {
        $query = $this->db->insert('quality_control', $data);
        return $query;
    }
    public function updateData($data)
    {
        // Pastikan menggunakan id_level atau kolom yang sesuai
        $this->db->where('id_qc', $data['id_qc']); // Menggunakan id_level sebagai kondisi
        $query = $this->db->update('quality_control', $data);
        return array('result' => $query);
    }


    public function deleteData($data)
    {
        $this->db->where('id_qc', $data['id_qc']); // Menggunakan id_level sebagai kondisi
        $success = $this->db->delete('quality_control');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }



    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM quality_control WHERE NAME='$INSTANSI' ";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    public function getAllQuality()
    {
        $query = $this->db->get('quality_control');
        return $query->result();
    }
}
    // Quality_model