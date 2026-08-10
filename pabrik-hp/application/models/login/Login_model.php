<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model 
{
    /**
     * Validasi login user
     * @param string $username
     * @param string $password (plain text)
     * @return object|null
     * 
     * @development: 
     * - Gunakan password_hash() & password_verify()
     * - Tambah last_login, login_attempt, captcha
     */
        public function get_valid_login($username, $password)
    {
        $sql = "SELECT
                    u.id_user,
                    u.id_karyawan,
                    u.username,
                    u.password,
                    u.role,
                    u.status,
                    k.nama_karyawan,
                    k.jabatan
                FROM users AS u
                LEFT JOIN karyawan AS k
                    ON u.id_karyawan = k.id_karyawan
                WHERE u.username = ?
                AND u.password = ?
                AND u.status = 'aktif'";

        $query = $this->db->query($sql, array($username, $password));

        return $query->row();
    }

    /**
     * Cari user berdasarkan username
     * @param string $username
     * @return object|null
     * 
     * @development: 
     * - Untuk validasi duplikat, lupa password, profil user
     */
    public function get_user_by_username($username)
    {
        return $this->db
            ->where('username', $username)
            ->get('users')
            ->row();
    }

    // ========== FUNGSI YANG BISA DIKEMBANGKAN ==========
    
    /*
    // Registrasi user dengan hash password
    public function register_user($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }
    
    // Verifikasi login dengan password hash
    public function verify_login($username, $password)
    {
        $user = $this->get_user_by_username($username);
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        return false;
    }
    
    // Update last login
    public function update_last_login($user_id)
    {
        $data = array(
            'last_login' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER['REMOTE_ADDR']
        );
        return $this->db->where('id_user', $user_id)->update('users', $data);
    }
    
    // Reset password
    public function reset_password($email, $new_password)
    {
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        return $this->db->where('email', $email)->update('users', array('password' => $hashed));
    }
    
    // Cek percobaan login
    public function check_login_attempts($username)
    {
        return $this->db->select('login_attempts, last_attempt')
            ->where('username', $username)->get('users')->row();
    }
    
    // Tambah percobaan login
    public function increment_login_attempts($username)
    {
        $this->db->set('login_attempts', 'login_attempts + 1', FALSE);
        $this->db->set('last_attempt', date('Y-m-d H:i:s'));
        return $this->db->where('username', $username)->update('users');
    }
    
    // Reset percobaan login (setelah berhasil)
    public function reset_login_attempts($username)
    {
        $this->db->set('login_attempts', 0);
        return $this->db->where('username', $username)->update('users');
    }
    
    // Aktivasi akun
    public function activate_account($username, $activation_code)
    {
        return $this->db->where('username', $username)
            ->where('activation_code', $activation_code)
            ->update('users', array('status' => 'aktif'));
    }
    */
}

/* End of file Login_model.php */