<?php
defined('BASEPATH') or exit('No direct script access allowed');

class KomponenController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model & cek login
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Komponen_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen komponen
    // @development: 
    // - Pindahkan cek akses ke konstruktor
    // - Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Komponen';
        $this->template->load('main_template', 'pabrik/@komponen', $data);
        
        // Cek akses modul komponen (id_modul=5)
        if (!cekAkses(5, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
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
    // Untuk mengedit data komponen
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
    // Insert data komponen baru
    // @param POST: data komponen
    // @return JSON (result)
    // @development: 
    // - Tambah validasi stok >= 0, harga >= 0
    // - Cek duplikasi nama_komponen
    function save()
    {
        cekAkses(5, 'can_insert'); // Cek akses insert
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }

    // ===================== UPDATE DATA =====================
    // Update data komponen
    // @param POST: data komponen (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi stok & harga
    function update()
    {
        cekAkses(5, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($data, true)); // Debug logging
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data komponen
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus (detail_produksi)
    // - Soft delete
    function delete()
    {
        cekAkses(5, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_komponen' => $data['id']); // PERBAIKI: seharusnya id_komponen
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_komponen
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_komponen']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA SUPPLIER =====================
    // Untuk dropdown pilihan supplier
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter supplier aktif
    // - Tambah sorting
    public function getSupplier()
    {
        $data = $this->db->get('supplier')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_supplier,
                'name'  => $row->nama_supplier 
            ];
        }
        echo json_encode($result);
    }
}