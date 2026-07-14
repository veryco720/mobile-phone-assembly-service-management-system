<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdukController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        //$this->load->helper('configsession');
        //cek_login();
        $this->load->model('pabrik/Produk_model', 'model');

        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
    }

    public function index()
    {
        $data['title'] = 'Halaman produk';
        //$data['session'] = session();
        $this->template->load('main_template', 'pabrik/@produk', $data);
    }



    function getData()
    {
        $data = array(
            'start'         => $_POST['start'],         //
            'length'        => $_POST['length'],
            'filtervalue'   => $_POST['filtervalue'],   //di pakai untuk costum filter. field / column name
            'filtertext'    => $_POST['filtertext']     //keyword pencariannya
        );
        $res = $this->model->getDataAll($data);     //menerima hasil eksekusi query dari model
        echo json_encode($res);
    }
    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['id']);
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
        $data = array('id_produk' => $data['id_produk']); // Pastikan bahwa id_produk yang digunakan
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }



    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_produk']);
        $res = array('res' => $check);
        echo json_encode($res);
    }
}
// Controller