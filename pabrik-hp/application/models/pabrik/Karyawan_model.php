<?php class Karyawan_model extends CI_Model
{


    public function getDataAll($data)
    {
        $queryall = $this->db->get('karyawan');
        $sql = "SELECT  `id_karyawan`, 
                        `nama_karyawan`,     
                        `jabatan`, 
                        `no_hp`,
                        `alamat`,
                        `status`

                        FROM karyawan 

        where " . $data['filtervalue'] . " like '%" . $data['filtertext'] . "%' limit " . $data["start"] . "," . $data['length'];
        $query = $this->db->query($sql); // query builder -> list data
        $data = $query->result();        // eksekusi query 
        $total = $queryall->num_rows();  // jumlah data
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }
    public function getDataId($id)
    {
        $sql = "SELECT * FROM karyawan WHERE id_karyawan='$id' ";
        $query = $this->db->query($sql);
        return $query->result();
    } 
    public function insertData($data)
    {
        $query = $this->db->insert('karyawan', $data);
        return $query;
    }
    public function updateData($data)
    {
        // Pastikan menggunakan id_level atau kolom yang sesuai
        $this->db->where('id_karyawan', $data['id_karyawan']); // Menggunakan id_level sebagai kondisi
        $query = $this->db->update('karyawan', $data);
        return array('result' => $query);
    }


    public function deleteData($data)
    {
        $this->db->where('id_karyawan', $data['id_karyawan']); // Menggunakan id_level sebagai kondisi
        $success = $this->db->delete('karyawan');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data memiliki relasi.'
        );
    }



    public function checkId($INSTANSI)
    {
        $sql = "SELECT * FROM karyawan WHERE karyawan='$INSTANSI' ";
        $query = $this->db->query($sql); 
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    public function getAllKaryawan()
    {
        $query = $this->db->get('karyawan');
        return $query->result();
    }
}
    // Model