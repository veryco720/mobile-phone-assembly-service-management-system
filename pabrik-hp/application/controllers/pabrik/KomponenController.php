<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KomponenController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // $this->load->helper('configsession');
        // cek_login();
        $this->load->model('pabrik/Komponen_model', 'model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Komponen';
        // $data['session'] = session();
        $this->template->load('main_template', 'pabrik/@komponen', $data);
    }

    function getData()
    {
        $data = array(
            'start' => $_POST['start'],
            'length' => $_POST['length'],
            'filtervalue' => $_POST['filtervalue'], // dipakai untukk custom filter.field/colum name
            'filtertext' => $_POST['filtertext'], // isi /keyword pencariannya
        );
        $res = $this->model->getDataAll($data); // menerima hasil eksekusi query dari model
        echo json_encode($res);
    }

    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['id_komponen']); // menerima hasil eksekusi query dari model
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
        $data = array('id_qc' => $data['id']); // Pastikan bahwa id_level yang digunakan
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }
 


    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_komponen']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    public function getSupplier() //fungsi untuk mengambil data supplier dan mengembalikannya dalam format JSON
    {
        $data = $this->db->get('supplier')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_supplier,
                'name'  => $row->nama_supplier 
            ];
        }
        echo json_encode($result);
    }
}
// Controller