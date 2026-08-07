<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProdukController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Produk_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul produk (id_modul=6)
        if (!cekAkses(6, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen produk
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman produk';
        $this->template->load('main_template', 'pabrik/@produk', $data);
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
    // Untuk mengedit data produk
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
    // Insert data produk baru
    // @param POST: data produk
    // @return JSON (result)
    // @development: 
    // - Tambah validasi format RAM/ROM
    // - Cek duplikasi nama_produk
    function save()
    {
        cekAkses(6, 'can_insert'); // Cek akses insert
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }

    // ===================== UPDATE DATA =====================
    // Update data produk
    // @param POST: data produk (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi format RAM/ROM
    function update()
    {
        cekAkses(6, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($data, true)); // Debug logging
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data produk
    // @param POST: id_produk
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus (gudang, produksi)
    // - Soft delete
    function delete()
    {
        cekAkses(6, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_produk' => $data['id_produk']);
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_produk
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_produk']);
        $res = array('res' => $check);
        echo json_encode($res);
    }
}