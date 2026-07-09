<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
                        
class Login_model extends CI_Model 
{
    public function get_valid_login($username, $password)
    {
        $sql = "SELECT
                    u.id_user,
                    u.username,
                    u.password,

                    -- j.jabatan AS nama_jabatan,
                    -- j.id_level,
                    -- l.level AS nama_level

                FROM users AS u
                -- LEFT JOIN tb_jabatan AS j ON u.id_jabatan = j.id_jabatan
                -- LEFT JOIN tb_level AS l ON j.id_level = l.id_level
                WHERE u.username = ?
                AND u.password = ?
                AND u.aktif = aktif";

        $query = $this->db->query($sql, array(
            $username,
            $password
        ));

        return $query->row();
    }

    // public function save_registrasi($data)
    // {
    //     return $this->db->insert('users', $data);
    // }

    // public function getJabatan()
    // {
    //     return $this->db
    //         ->where('id_level', 3)
    //         ->order_by('jabatan','ASC')
    //         ->get('tb_jabatan')
    //         ->result();
    // }

    // public function get_user_by_username($username)
    // {
    //     return $this->db
    //         ->where('username', $username)
    //         ->get('users')
    //         ->row();
    // }
   
}


/* End of file Login_model.php and path \application\models\login\Login_model.php */
