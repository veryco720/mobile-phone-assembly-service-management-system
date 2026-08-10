<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class KaryawanController extends CI_Controller {

    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Karyawan_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul karyawan (id_modul=3)
        if (!cekAkses(3, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini'); 
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen karyawan
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Karyawan';
        $this->template->load('main_template', 'pabrik/@karyawan', $data);
    }

    // ===================== AMBIL DATA UNTUK DATATABLE =====================
    // Server-side DataTable
    // @param POST: start, length, filtervalue, filtertext
    // @return JSON
    // @development: Tambah sorting & pagination
    function getData()
    {
        $data = array(
            'start'         => $_POST['start'],
            'length'        => $_POST['length'],
            'filtervalue'   => $_POST['filtervalue'],
            'filtertext'    => $_POST['filtertext']
        );
        $res = $this->model->getDataAll($data);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA BY ID =====================
    // Untuk mengedit data karyawan
    // @param POST: id
    // @return JSON
    // @development: Tambah validasi jika data tidak ditemukan
    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['id']);
        echo json_encode($res);
    }

    // ===================== SAVE DATA =====================
    // Insert data karyawan baru
    // @param POST: data karyawan
    // @return JSON (result)
    // @development: 
    // - Tambah validasi no_hp
    // - Cek duplikasi nama
    function save()
    {
        cekAkses(3, 'can_insert'); // Cek akses insert
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }

    // ===================== UPDATE DATA =====================
    // Update data karyawan
    // @param POST: data karyawan (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi data
    function update()
    {
        cekAkses(3, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($data, true)); // Debug logging
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data karyawan
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus (user, produksi)
    // - Soft delete
    function delete()
    {
        cekAkses(3, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_karyawan' => $data['id']);
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_karyawan
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_karyawan']);
        $res = array('res' => $check);
        echo json_encode($res);
    }
}

/* End of file KaryawanController.php */