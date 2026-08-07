<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DetailController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, helper, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/detail_model', 'model');
        $this->load->helper(array('url', 'akses'));

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul detail (id_modul=8)
        if (!cekAkses(8, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini'); 
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen detail produksi
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Detail';
        $this->template->load('main_template', 'pabrik/@detail', $data);
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
    // Untuk mengedit detail produksi
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
    // Insert detail produksi baru
    // @param POST: data detail
    // @return JSON (result)
    // @development: 
    // - Tambah validasi data
    // - Cek duplikasi
    function save()
    {
        if (!cekAkses(8, 'can_insert')); // Cek akses insert
        $data   = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        echo json_encode(["result" => $insert]);
    }

    // ===================== UPDATE DATA =====================
    // Update detail produksi
    // @param POST: data detail (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi data
    function update()
    {
        if (!cekAkses(8, 'can_update')); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus detail produksi
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus
    // - Soft delete
    function delete()
    {
        if (!cekAkses(8, 'can_delete')); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id_detail' => $data['id']]);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_detail
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_detail']);
        echo json_encode(['res' => $check]);
    }

    // ===================== AMBIL DATA PRODUKSI =====================
    // Untuk dropdown pilihan produksi
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter produksi aktif
    // - Tambah join dengan produk
    public function getProduksi()
    {
        $data = $this->model->getProduksi();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produksi,
                'name'  => $row->id_produksi . ' - ' . $row->nama_produk
            ];
        }
        echo json_encode($result);
    }

    // ===================== AMBIL DATA KOMPONEN =====================
    // Untuk dropdown pilihan komponen
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter komponen aktif
    // - Tambah sorting
    public function getKomponen()
    {
        $data = $this->db->get('komponen')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_komponen,
                'name'  => $row->nama_komponen
            ];
        }
        echo json_encode($result);
    }
}