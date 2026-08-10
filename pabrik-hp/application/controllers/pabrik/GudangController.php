<?php
defined('BASEPATH') or exit('No direct script access allowed');

class GudangController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/gudang_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul gudang (id_modul=10)
        if (!cekAkses(10, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen gudang
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Gudang';
        $this->template->load('main_template', 'pabrik/@gudang', $data);
    }

    // ===================== AMBIL DATA UNTUK DATATABLE =====================
    // Server-side DataTable
    // @param POST: start, length, filtervalue, filtertext
    // @return JSON
    // @development: Tambah sorting & pagination
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

    // ===================== AMBIL DATA BY ID =====================
    // Untuk mengedit data gudang
    // @param POST: id
    // @return JSON
    // @development: Tambah validasi jika data tidak ditemukan
    function getDataSelect()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->getDataId($data['id']);
        echo json_encode($res);
    }

    // ===================== SAVE DATA =====================
    // Insert data gudang baru
    // @param POST: data gudang
    // @return JSON (result)
    // @development: 
    // - Tambah validasi stok >= 0
    // - Cek duplikasi produk di lokasi
    function save()
    {
        cekAkses(10, 'can_insert'); // Cek akses insert
        $data   = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        echo json_encode(["result" => $insert]);
    }

    // ===================== UPDATE DATA =====================
    // Update data gudang
    // @param POST: data gudang (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi stok >= 0
    function update()
    {
        cekAkses(10, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data gudang
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus
    // - Soft delete
    function delete()
    {
        cekAkses(10, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id_gudang' => $data['id']]);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_gudang
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_gudang']);
        echo json_encode(['res' => $check]);
    }

    // ===================== AMBIL DATA PRODUK =====================
    // Untuk dropdown pilihan produk
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter produk aktif
    // - Tambah sorting
    public function getProduk()
    {
        $data = $this->db->get('produk_hp')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produk,
                'name'  => $row->nama_produk
            ];
        }
        echo json_encode($result);
    }
}