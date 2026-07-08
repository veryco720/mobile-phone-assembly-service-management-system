<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/detail_model', 'model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Detail';
        $this->template->load('home_template', 'pabrik/@detail', $data);
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
        $res  = $this->model->deleteData(['id_detail' => $data['id']]);
        echo json_encode($res);
    }

    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_detail']);
        echo json_encode(['res' => $check]);
    }

    public function getProduksi()
    {
        $data = $this ->db->get('produksi')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produksi,
                'name' => $row->id_produksi
            ];
        }
        echo json_encode($result);
    }

    public function getKomponen()
    {
        $data = $this ->db->get('komponen')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_komponen,
                'name' => $row->nama_komponen
            ];
        }
        echo json_encode($result);
    }
}