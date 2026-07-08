 <?php
defined('BASEPATH') or exit('No direct script access allowed');

class GudangController extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/gudang_model', 'model');
    }

    public function index()
    {
        $data['title'] = 'Halaman Gudang';
        $this->template->load('home_template', 'pabrik/@gudang', $data);
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
        $res  = $this->model->deleteData(['id_gudang' => $data['id']]);
        echo json_encode($res);
    }

    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_gudang']);
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
}