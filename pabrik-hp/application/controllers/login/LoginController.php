<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class LoginController extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->database(); 
        $this->load->library('session'); 
        $this->load->helper(array('url', 'encryption', 'percobaan'));
        $this->load->model('login/Login_model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Login';
        $data['contents'] = $this->load->view('login/@login', $data, TRUE);
        $this->load->view('home_template', $data);
    }

    /* Dipanggil via ajaxPost - harus return JSON, bukan redirect */
    public function get_valid_login()
    {
        $data     = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
        $password = $data['password'];

        
        $user_check = $this->Login_model->get_user_by_username($username);
        if (!$user_check) {
            echo json_encode([
                'result' => false,
                'message' => 'Username tidak ditemukan'
            ]);
            return;
        }

        // helper percobaan_login_helper; 

        if ($user_check->aktif == 0) {
            echo json_encode([
                'result' => false,
                'message' => 'Akun tidak aktif. Hubungi administrator.'
            ]);
            return;
        }

        if ($user_check->aktif == 2) {

            $block = check_account_block($user_check);

            if ($block['blocked']) {
                echo json_encode([
                    'result' => false,
                    'message' => 'Akun masih diblokir. Sisa waktu: '
                        . remaining_block_time($block['remaining_minutes'])
                ]);
                return;
            }

            $user_check = $this->Login_model->get_user_by_username($username);
        }
        

        $password_encrypt = encrypt_password($password);
        $user = $this->Login_model->get_valid_login($username, $password_encrypt);

        if ($user) {

            reset_login_attempt($user_check);

            $this->session->set_userdata([
                'is_login'   => true,
                'id_user'    => $user->id,
                'username'   => $user->username,

                'id_jabatan' => $user->id_jabatan,
                'jabatan'    => $user->nama_jabatan,

                'id_level'   => $user->id_level,
                'level'      => $user->nama_level
                
            ]);
            // Return JSON - redirect dilakukan di JS (_login.php)
            echo json_encode(['result' => true]);
        } else {

    $attempts = increase_login_attempt($user_check);

    if ($attempts >= 3) {

        echo json_encode([
            'result' => false,
            'message' => 'Akun diblokir karena telah 3 kali gagal login. Silakan coba lagi dalam 24 jam.'
        ]);
        return;
    }

    echo json_encode([
        'result' => false,
        'message' => 'Password salah. Percobaan ke-' . $attempts . ' dari 3.'
    ]);
}
    }

    // public function registrasi()
    // {
    //     $data['title']    = 'Halaman Registrasi';
    //     $data['contents'] = $this->load->view('login/@registrasi1', $data, TRUE);
    //     $this->load->view('home_template', $data);
    // }

    /* Pola sama persis seperti PelangganController - return array langsung */
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

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login/LoginController');
    }
}

/* End of file LoginController.php */