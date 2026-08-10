<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class UserController extends CI_Controller {

    // ===================== KONSTRUKTOR =====================
    // Load library, helper, model & cek login
    // @development: Tambah cek akses modul user (id_modul=2)
    function __construct()
    {
        parent::__construct();
        $this->load->library('session'); 
        $this->load->helper(array('url', 'encryption'));
        $this->load->model('pabrik/User_model', 'model');
    
        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // @development: Tambah cek akses
        // if (!cekAkses(2, 'can_view')) {
        //     show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        // }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen user
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman User';
        $this->template->load('main_template', 'pabrik/@user', $data);
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
    // Untuk mengedit data user
    // @param POST: id_user
    // @return JSON
    // @development: Tambah validasi jika data tidak ditemukan
    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res = $this->model->getDataId($data['id_user']);
        echo json_encode($res);
    }

    // ===================== SAVE DATA =====================
    // Insert data user baru
    // @param POST: data user
    // @return JSON (result)
    // @development: 
    // - Tambah cek akses can_insert
    // - Cek duplikasi username
    // - Validasi role
    function save()
    {
        // @development: cekAkses(2, 'can_insert');
        $data = json_decode(file_get_contents('php://input'), true);
        $data['password'] = encrypt_password($data['password']); // Enkripsi password
        $insert = $this->model->insertData($data);
        echo json_encode(array('result' => $insert));
    }

    // ===================== UPDATE DATA =====================
    // Update data user
    // @param POST: data user (dengan id)
    // @return JSON (result)
    // @development: 
    // - Tambah cek akses can_update
    // - Cek apakah data ada
    // - Cek duplikasi username
    function update()
    {
        // @development: cekAkses(2, 'can_update');
        $data = json_decode(file_get_contents('php://input'), true);
        // Enkripsi password hanya jika diisi (tidak kosong)
        if ($data['password'] != "") {
            $data['password'] = encrypt_password($data['password']);
        }
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data user
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Tambah cek akses can_delete
    // - Cek relasi sebelum hapus
    // - Jangan hapus user yang sedang login
    function delete()
    {
        // @development: cekAkses(2, 'can_delete');
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_user' => $data['id']);
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_user
    // @return JSON (res)
    // @development: Perbaiki logika fungsi (cek username)
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_user']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA KARYAWAN =====================
    // Untuk dropdown pilihan karyawan
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter karyawan yang belum punya user
    // - Tambah sorting
    public function getKaryawan()
    {
        $data = $this->model->getKaryawanDanRole();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_karyawan,
                'name'  => $row->nama_karyawan . ' (' . $row->role . ')'
            ];
        }
        echo json_encode($result);
    }
}

/* End of file UserController.php */