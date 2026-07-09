<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QualityController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        // $this->load->helper('configsession');
        // cek_login();
        $this->load->model('pabrik/Quality_model', 'model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Quality Control';
        // $data['session'] = session();
        $this->template->load('main_template', 'pabrik/@quality', $data);
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
        $res = $this->model->getDataId($data['id_qc']); // menerima hasil eksekusi query dari model
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
        $check = $this->model->checkId($data['id_qc']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    public function getProduksi() //fungsi untuk mengambil data produksi dan mengembalikannya dalam format JSON
    {
        $data = $this->db->get('produksi')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produk,
                'name'  => $row->id_produk 
            ];
        }
        echo json_encode($result);
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
// Controller