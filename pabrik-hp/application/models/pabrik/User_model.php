<?php class User_model extends CI_Model
{

    public function getDataAll($data)
    {
        $queryall = $this->db->get('users');
        $sql = "SELECT 
                tb1.id_user,
                tb1.id_karyawan,
                tb1.username,
                tb1.password,
                tb1.role,
                tb1.status,

                k.nama_karyawan as nama_karyawan

                FROM users AS tb1

                LEFT JOIN karyawan AS k ON tb1.id_karyawan = k.id_karyawan

                WHERE " . $data['filtervalue'] . " LIKE '%" . $data['filtertext'] . "%'
                ORDER BY tb1.id_user ASC
                LIMIT " . $data["start"] . "," . $data['length'];
        $query = $this->db->query($sql);
        $data = $query->result();
        $total = $queryall->num_rows();
        $dataRecord = array(
            "RecordsTotal" => $total,
            "RecordsFiltered" => $total,
            "Data" => $data,
        );
        return $dataRecord;
    }

    public function getDataId($id)
    {
        $sql = "SELECT * FROM users WHERE id_user='$id'";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function insertData($data)
    {
        $query = $this->db->insert('users', $data);
        return $query;
    }

    public function updateData($data)
    {
        $this->db->where('id_user', $data['id_user']);
        $query = $this->db->update('users', $data);
        return array('result' => $query);
    }

    public function deleteData($data)
    {
        $this->db->where('id_user', $data['id_user']);
        $success = $this->db->delete('users');
        return array(
            'result' => $success,
            'message' => $success ? 'Data berhasil dihapus.' : 'Gagal menghapus data.'
        );
    }

    public function checkId($id)
    {
        $sql = "SELECT * FROM users WHERE id_user='$id'";
        $query = $this->db->query($sql);
        $total = $query->num_rows();
        if ($total > 0) {
            return "Data Sama";
        } else {
            return "OK";
        }
    }

    public function getAllUser()
    {
        $query = $this->db->get('users');
        return $query->result();
    }


}// Model