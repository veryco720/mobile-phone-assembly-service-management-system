<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    // ===================== KONSTRUKTOR =====================
    // Cek login dan load model dashboard
    // @development: Tambah cache untuk data statistik
    public function __construct()
    {
        parent::__construct();
        $this->load->model('pabrik/Dashboard_model');

        // Cek apakah user sudah login
        if ($this->session->userdata('is_login') != true) {
            redirect('login/LoginController');
        }
    }

    // ===================== HALAMAN DASHBOARD =====================
    // Menampilkan dashboard dengan data statistik
    // @development: 
    // - Tambah grafik (Chart.js, Highcharts)
    // - Tambah filter per periode (hari, minggu, bulan)
    // - Tambah data real-time
    public function index()
    {
        $data['title'] = "DashboardController";

        // ===== STATISTIK UTAMA =====
        // @development: Bisa di-cache untuk performance
        $data['total_produk']      = $this->Dashboard_model->totalProduk();
        $data['total_karyawan']    = $this->Dashboard_model->totalKaryawan();
        $data['total_supplier']    = $this->Dashboard_model->totalSupplier();
        $data['total_komponen']    = $this->Dashboard_model->totalKomponen();

        // ===== STATUS PRODUKSI =====
        // @development: Tambah chart untuk visualisasi
        $data['produksi']          = $this->Dashboard_model->produksiBerjalan(); // Status Perakitan
        $data['qc']                = $this->Dashboard_model->qcBerjalan();      // Status QC
        $data['selesai']           = $this->Dashboard_model->produksiSelesai(); // Status Selesai

        // ===== STOK GUDANG =====
        // @development: Tambah data stok per produk
        $data['stok']              = $this->Dashboard_model->stokGudang();

        // ===== DATA PRODUKSI TERBARU =====
        // @development: Bisa ditambah pagination atau filter
        $data['produksi_terbaru']  = $this->Dashboard_model->produksiTerbaru();

        // Load view dengan template
        $this->template->load('main_template', 'pabrik/@dashboard', $data);
    }
}