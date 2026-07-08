<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProduksiController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/produksi_model', 'model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Produksi';
        $this->template->load('home_template', 'pabrik/@produksi', $data);
    }

    function getData()
    {
        $data = array(
            'start'       => $_POST['start'],
            'length'      => $_POST['length'],
            'filtervalue' => $_POST['filtervalue'],
            'filtertext'  => $_POST['filtertext'],
        );
        $res = $this->model->getDataAll($data);
        echo json_encode($res);
    }

    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->getDataId($data['id']);
        echo json_encode($res);
    }

    function save()
    {
        $data   = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        echo json_encode(["result" => $insert]);
    }

    function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    function delete()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id_produksi' => $data['id']]);
        echo json_encode($res);
    }

    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_produksi']);
        echo json_encode(['res' => $check]);
    }

    public function getProduk()
    {
        $data = $this->db->get('produk_hp')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produk,
                'name' => $row->nama_produk
            ];
        }
        echo json_encode($result);
    }

    public function getKaryawan()
    {
        $data = $this->db->get('karyawan')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_karyawan,
                'name' => $row->nama_karyawan
            ];
        }
        echo json_encode($result);
    }
    public function saveProduk()
{
    $name = $this->input->post('name');
    $data = ['nama_produk' => $name]; // sesuaikan nama kolom di tabel produk

    $result = $this->Produksi_model->saveProduk($data); // sesuaikan nama model/method

    echo json_encode(['result' => $result ? true : false]);
}
}