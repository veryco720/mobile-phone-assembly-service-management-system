<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        //$this->load->helper('configsession');
        //cek_login();
        $this->load->model('pabrik/User_model', 'model');

        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
    }

    public function index()
    {
        $data['title'] = 'Halaman User';
        //$data['session'] = session();
        $this->template->load('main_template', 'pabrik/@user', $data);
    }



    function getData()
    {
        $data = array(
            'start'         => $_POST['start'],  // 
            'length'        => $_POST['length'], // 
            'filtervalue'   => $_POST['filtervalue'], // dipaikai untuk custom filter. field / colum name
            'filtertext'    => $_POST['filtertext']  // keyword untuk pencarian
        );
        $res = $this->model->getDataAll($data); // menerima hasil eksekusi query dari model.
        echo json_encode($res);
    }

    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['id_user']);
        echo json_encode($res);
    }
    function save()
    {
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }
    function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        // Logging for debugging
        error_log(print_r($data, true));

        $res = $this->model->updateData($data);
        echo json_encode($res);
    }


    function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_user' => $data['id']); // Pastikan bahwa id_level yang digunakan
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }



    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_user']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    public function getKaryawan() //fungsi untuk mengambil data karyawan dan mengembalikannya dalam format JSON
    {
        $data = $this->db->get('karyawan')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_karyawan,
                'name'  => $row->nama_karyawan
            ];
        }
        echo json_encode($result);
    }
}

/* End of file KaryawanController.php and path \application\controllers\pabrik\KaryawanController.php */
