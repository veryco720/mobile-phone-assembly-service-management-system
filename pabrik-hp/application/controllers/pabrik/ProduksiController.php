<?php
defined('BASEPATH') or exit('No direct script access allowed');

class ProduksiController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Load model, cek login & akses
    // @development: Tambah cache atau logging
    function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/produksi_model', 'model');

        // Cek login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
        // Cek akses modul produksi (id_modul=7)
        if (!cekAkses(7, 'can_view')) {
            show_error(403, 'Anda tidak memiliki hak akses untuk melihat halaman ini');
        }
    }

    // ===================== HALAMAN INDEX =====================
    // Menampilkan halaman manajemen produksi
    // @development: Tambah breadcrumb atau statistik
    public function index()
    {
        $data['title'] = 'Halaman Produksi';
        $this->template->load('main_template', 'pabrik/@produksi', $data);
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
    // Untuk mengedit data produksi
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
    // Insert data produksi baru
    // @param POST: data produksi
    // @return JSON (result)
    // @development: 
    // - Validasi target > 0
    // - Cek ketersediaan produk & karyawan
    function save()
    {
        cekAkses(7, 'can_insert');

        // Ambil data dari frontend
        $data = json_decode(file_get_contents("php://input"), true);

        // ==============================
        // AMBIL USER DARI SESSION
        // ==============================
        $id_karyawan = $this->session->userdata('id_karyawan');
        $login_at    = $this->session->userdata('login_at');

        // Pastikan user memiliki karyawan
        if (empty($id_karyawan)) {
            echo json_encode([
                'result'  => false,
                'message' => 'Data karyawan pada akun login tidak ditemukan.'
            ]);
            return;
        }

        // ==============================
        // TANGGAL PRODUKSI = TANGGAL LOGIN
        // ==============================
        $tanggal_produksi = date(
            'Y-m-d H:i:s',
            strtotime($login_at)
        );

        // ==============================
        // DATA YANG BOLEH DARI FORM
        // ==============================
        $insertData = [
            'id_produk'        => $data['id_produk'],
            'id_karyawan'      => $id_karyawan,
            'tanggal_produksi' => $tanggal_produksi,
            'target'           => $data['target'],
            'jumlah_selesai'   => !empty($data['jumlah_selesai'])
                                    ? $data['jumlah_selesai']
                                    : 0,
            'status'           => !empty($data['status'])
                                    ? $data['status']
                                    : 'Perakitan'
        ];

        // Simpan
        $insert = $this->model->insertData($insertData);

        echo json_encode([
            "result" => $insert
        ]);
    }

    // ===================== UPDATE DATA =====================
    // Update data produksi
    // @param POST: data produksi (dengan id)
    // @return JSON (result)
    // @development: 
    // - Cek apakah data ada
    // - Validasi jumlah_selesai <= target
    function update()
    {
        cekAkses(7, 'can_update'); // Cek akses update
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->updateData($data);
        echo json_encode($res);
    }

    // ===================== DELETE DATA =====================
    // Hapus data produksi
    // @param POST: id
    // @return JSON (result, message)
    // @development: 
    // - Cek relasi sebelum hapus (detail_produksi, qc)
    // - Soft delete
    function delete()
    {
        cekAkses(7, 'can_delete'); // Cek akses delete
        $data = json_decode(file_get_contents('php://input'), true);
        $res  = $this->model->deleteData(['id_produksi' => $data['id']]);
        echo json_encode($res);
    }

    // ===================== CEK DUPLIKASI =====================
    // Validasi saat insert/update
    // @param POST: id_produksi
    // @return JSON (res)
    // @development: Perbaiki logika fungsi
    function checkId()
    {
        $data  = json_decode(file_get_contents('php://input'), true);
        $check = $this->model->checkId($data['id_produksi']);
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

    // ===================== SAVE PRODUK (FUNGSI BARU) =====================
    // @development: 
    // - Perbaiki: model 'Produksi_model' tidak ada
    // - Gunakan model 'produksi_model' atau buat model terpisah
    public function saveProduk()
    {
        $name = $this->input->post('name');
        $data = ['nama_produk' => $name];
        
        // PERBAIKI: Model 'Produksi_model' tidak ditemukan
        $result = $this->Produksi_model->saveProduk($data);
        
        echo json_encode(['result' => $result ? true : false]);
    }

    
}