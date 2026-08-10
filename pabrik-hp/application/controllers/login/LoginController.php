<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    // ===================== KONSTRUKTOR =====================
    // Load library, helper, dan model yang dibutuhkan
    // @development: Tambah library untuk captcha, rate limiting, atau logging
    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
        $this->load->library('session'); 
        $this->load->helper(array('url', 'encryption'));
        $this->load->model('login/Login_model');
    }

    // ===================== HALAMAN LOGIN =====================
    // Menampilkan halaman login
    // @development: Tambah captcha, remember me, atau redirect jika sudah login
    public function index()
    {
        $data['title'] = 'Halaman Login';
        $data['contents'] = $this->load->view('login/@login', $data, TRUE);
        $this->load->view('home_template', $data);
    }

    // ===================== PROSES LOGIN =====================
    // Dipanggil via AJAX, return JSON
    // @development: 
    // - Tambah validasi captcha
    // - Tambah rate limiting (percobaan login)
    // - Log aktivitas login
    public function get_valid_login()
    {
        // Ambil data dari request JSON
        $data     = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];

        // Cek apakah username terdaftar
        // @development: Tambah notifikasi "username tidak ditemukan" atau "password salah"
        $user_check = $this->Login_model->get_user_by_username($username);
        if (!$user_check) {
            echo json_encode([
                'result' => false,
                'message' => 'Username tidak ditemukan'
            ]);
            return;
        }

        // ===== FITUR YANG DINONAKTIFKAN (BISA DIKEMBANGKAN) =====
        // helper percobaan_login_helper; 

        // Cek status akun (aktif/nonaktif)
        // if ($user_check->aktif == 0) {
        //     echo json_encode([
        //         'result' => false,
        //         'message' => 'Akun tidak aktif. Hubungi administrator.'
        //     ]);
        //     return;
        // }

        // Cek blokir akun (jika terlalu banyak gagal login)
        // if ($user_check->aktif == 2) {
        //     $block = check_account_block($user_check);
        //     if ($block['blocked']) {
        //         echo json_encode([
        //             'result' => false,
        //             'message' => 'Akun masih diblokir. Sisa waktu: '
        //                 . remaining_block_time($block['remaining_minutes'])
        //         ]);
        //         return;
        //     }
        //     $user_check = $this->Login_model->get_user_by_username($username);
        // }
        
        // Enkripsi password dan validasi login
        $password_encrypt = encrypt_password($password);
        $user = $this->Login_model->get_valid_login($username, $password_encrypt);

        if ($user) {
            // ===== LOGIN BERHASIL =====
            // reset_login_attempt($user_check); // Reset percobaan login

            // Set session data
            $this->session->set_userdata([
                'is_login'       => true,
                'id_user'        => $user->id_user,
                'id_karyawan'    => $user->id_karyawan,
                'nama_karyawan'  => $user->nama_karyawan,
                'jabatan'        => $user->jabatan,
                'username'       => $user->username,
                'role'           => $user->role,

                // Tanggal saat user berhasil login
                'login_at'       => date('Y-m-d H:i:s')
            ]);

            // Return JSON sukses (redirect di JS)
            echo json_encode(['result' => true]);
        }
        else {
            // ===== LOGIN GAGAL =====
            // @development: Tambah counter percobaan login
            echo json_encode([
                'result' => false,
                'message' => 'Password yang Anda masukkan salah.'
            ]);

            // ===== FITUR BLOKIR AKUN =====
            // $attempts = increase_login_attempt($user_check);
            // if ($attempts >= 3) {
            //     echo json_encode([
            //         'result' => false,
            //         'message' => 'Akun diblokir karena telah 3 kali gagal login. Silakan coba lagi dalam 24 jam.'
            //     ]);
            //     return;
            // }
            // echo json_encode([
            //     'result' => false,
            //     'message' => 'Password salah. Percobaan ke-' . $attempts . ' dari 3.'
            // ]);
        }
    }

    // ===================== HALAMAN REGISTRASI (DINONAKTIFKAN) =====================
    // Menampilkan halaman registrasi
    // @development: Aktifkan jika diperlukan
    // public function registrasi()
    // {
    //     $data['title']    = 'Halaman Registrasi';
    //     $data['contents'] = $this->load->view('login/@registrasi1', $data, TRUE);
    //     $this->load->view('home_template', $data);
    // }

    // ===================== AMBIL DATA JABATAN (DINONAKTIFKAN) =====================
    // Untuk dropdown registrasi
    // @development: Aktifkan jika diperlukan
    // public function getJabatan()
    // {
    //     $data = $this->Login_model->getJabatan();
    //     $result = [];
    //     foreach ($data as $row) {
    //         $result[] = [
    //             'value' => $row->id_jabatan,
    //             'name'  => $row->jabatan
    //         ];
    //     }
    //     echo json_encode($result);
    // }

    // ===================== SAVE REGISTRASI (DINONAKTIFKAN) =====================
    // Menyimpan data registrasi
    // @development: Aktifkan jika diperlukan
    // public function save_registrasi()
    // {
    //     $data = json_decode(file_get_contents('php://input'), true);
    //     $insert = [
    //         'username'   => $data['username'],
    //         'password'   => encrypt_password($data['password']),
    //         'id_jabatan' => $data['id_jabatan']
    //     ];
    //     $result = $this->Login_model->save_registrasi($insert);
    //     echo json_encode(['result' => $result]);
    // }

    // ===================== LOGOUT =====================
    // Hapus session dan redirect ke halaman login
    // @development: Tambah log logout
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login/LoginController');
    }
}

/* End of file LoginController.php */