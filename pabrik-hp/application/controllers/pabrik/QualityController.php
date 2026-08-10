<?php
defined('BASEPATH') or exit('No direct script access allowed');

class QualityController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Quality_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul quality (id_modul=9)
        if (!cekAkses(9, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen quality control
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Quality Control';
        $this->template->load('main_template', 'pabrik/@quality', $data);
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
    // Untuk mengedit data quality control
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
    // Insert data quality control baru
    // @param POST: data qc
    // @return JSON (result)
    // @development: 
    // - Validasi hasil_qc (Lulus/Tidak Lulus)
    // - Cek duplikasi QC per produksi
    function save()
    {
        cekAkses(9, 'can_insert'); // Cek akses insert
        $data = json_decode(file_get_contents("php://input"), true);
        $insert = $this->model->insertData($data);
        $res = array("result" => $insert);
        echo json_encode($res);
    }

    // ===================== UPDATE DATA =====================
    // Update data quality control
    // @param POST: data qc (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi hasil_qc
    function update()
    {
        cekAkses(9, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($data, true)); // Debug logging
        $res = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data quality control
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus
    // - Soft delete
    function delete()
    {
        cekAkses(9, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $data = array('id_qc' => $data['id']);
        $res = $this->model->deleteData($data);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_qc
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_qc']);
        $res = array('res' => $check);
        echo json_encode($res);
    }

    // ===================== AMBIL DATA PRODUKSI =====================
    // Untuk dropdown pilihan produksi
    // @return JSON (value, name)
    // @development: 
    // - PERBAIKI: value-nya seharusnya id_produksi
    // - Tambah filter produksi yang belum di-QC
    public function getProduksi()
    {
        $data = $this->db->get('produksi')->result();
        $result = [];
        foreach ($data as $row) {
            $result[] = [
                'value' => $row->id_produksi, // PERBAIKI: id_produksi bukan id_produk
                'name'  => $row->id_produksi 
            ];
        }
        echo json_encode($result);
    }

    // ===================== AMBIL DATA KARYAWAN =====================
    // Untuk dropdown pilihan karyawan (dengan role)
    // @return JSON (value, name)
    // @development: 
    // - Tambah filter karyawan dengan role QC
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