<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class ProfileController extends CI_Controller {

    // ===================== KONSTRUKTOR =====================
    // Load library, helper, model & cek login
    // @development: Tambah logging aktivitas
    public function __construct()
    {
        parent::__construct();

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }

        $this->load->library('session');
        $this->load->helper(['url', 'encryption']);
        $this->load->model('pabrik/Profile_model', 'model');
    }

    // ===================== HALAMAN PROFILE =====================
    // Menampilkan halaman profile user
    // @development: 
    // - Tambah form edit profile (email, no_hp, foto)
    // - Tambah statistik aktivitas user
    public function index()
    {
        $data['title'] = 'Halaman Profil';
        $data['role'] = $this->session->userdata('role');
        $this->template->load('main_template', 'pabrik/@profil', $data);
    }

    // ===================== UBAH PASSWORD =====================
    // Proses ubah password via AJAX
    // @param POST: password_lama, password_baru, konfirmasi_password
    // @return JSON (result, message)
    // @development: 
    // - Tambah validasi password baru != password lama
    // - Tambah validasi konfirmasi password
    // - Hash password dengan password_hash()
    // - Log perubahan password
    public function ubahPassword()
    {
        // Ambil data dari request JSON
        $data = json_decode(file_get_contents('php://input'), true);
        
        // Ambil ID user dari session
        $id_user = $this->session->userdata('id_user');

        // Ambil data user dari database
        $user = $this->model->getUser($id_user);
        if (!$user) {
            echo json_encode([
                'result' => false,
                'message' => 'User tidak ditemukan'
            ]);
            return;
        }
        
        // Enkripsi password lama dan validasi
        $password_lama = encrypt_password($data['password_lama']);
        if ($password_lama != $user->password) {
            echo json_encode([
                'result' => false,
                'message' => 'Password lama salah'
            ]);
            return;
        }
        
        // Enkripsi password baru
        $password_baru = encrypt_password($data['password_baru']);
        
        // Update password di database
        $result = $this->model->updatePassword($id_user, $password_baru);
        
        // Return response
        echo json_encode([
            'result' => $result,
            'message' => 'Password berhasil diubah'
        ]);
    }
}

/* End of file ProfileController.php */