<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class AksesController extends CI_Controller {

    // ===================== KONSTRUKTOR =====================
    // Cek login dan load model akses
    // @development: Tambah cache atau logging
    public function __construct()
    {
        parent::__construct();
        // Cek apakah user sudah login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        $this->load->model('pabrik/Akses_model', 'model');
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen akses
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Akses';
        $this->template->load('main_template', 'pabrik/@akses', $data);
    }

    // ===================== AMBIL DATA UNTUK DATATABLE =====================
    // Server-side DataTable
    // @param POST: start, length, filtervalue, filtertext
    // @return JSON
    // @development: Tambah sorting & pagination
    public function getData()
    {
        $data = array(
            'start'         => $_POST['start'],         // Offset data
            'length'        => $_POST['length'],        // Jumlah data per halaman
            'filtervalue'   => $_POST['filtervalue'],   // Kolom yang difilter
            'filtertext'    => $_POST['filtertext']     // Keyword pencarian
        );
        $res = $this->model->getDataAll($data);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA BY ID =====================
    // Untuk mengedit akses berdasarkan role
    // @param POST: role
    // @return JSON
    // @development: Tambah validasi jika data tidak ditemukan
    public function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['role']);
        echo json_encode($res);
    }

    // ===================== UPDATE AKSES =====================
    // Update hak akses untuk role tertentu
    // @param POST: data (role + daftar akses)
    // @return JSON (result)
    // @development: 
    // - Tambah logging perubahan akses
    // - Tambah validasi role & modul
    public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== AMBIL AKSES BERDASARKAN ROLE =====================
    // Untuk menampilkan checkbox akses per modul
    // @param POST: role
    // @return JSON (daftar modul + hak akses)
    // @development: 
    // - Tambah cache untuk performance
    // - Filter modul yang aktif
    function getAkses()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $data = $this->model->getAksesbyRole($data['role']);
        echo json_encode($data);
    }
}

/* End of file AksesController.php */