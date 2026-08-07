<?php
defined('BASEPATH') or exit('No direct script access allowed');

class SupplierController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Supplier_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul supplier (id_modul=4)
        if (!cekAkses(4, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen supplier
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman supplier';
        $this->template->load('main_template', 'pabrik/@supplier', $data);
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
    // Untuk mengedit data supplier
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
    // Insert data supplier baru
    // @param POST: data supplier
    // @return JSON (result)
    // @development: 
    // - Validasi format email
    // - Validasi format telepon
    // - Cek duplikasi nama_supplier
    function save()
    {
        cekAkses(4, 'can_insert'); // Cek akses insert
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }

    // ===================== UPDATE DATA =====================
    // Update data supplier
    // @param POST: data supplier (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi email & telepon
    function update()
    {
        cekAkses(4, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($data, true)); // Debug logging
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data supplier
    // @param POST: id_supplier
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus (komponen)
    // - Soft delete
    function delete()
    {
        cekAkses(4, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_supplier' => $data['id_supplier']);
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_supplier
    // @return JSON (res)
    // @development: Perbaiki logika fungsi (cek nama_supplier)
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_supplier']);
        $res = array('res' => $check);
        echo json_encode($res);
    }
}