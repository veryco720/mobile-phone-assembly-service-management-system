<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('pabrik/Dashboard_model');
    }

    public function index()
    {
        $data['title'] = "DashboardController";

        $data['total_produk']      = $this->Dashboard_model->totalProduk();
        $data['total_karyawan']    = $this->Dashboard_model->totalKaryawan();
        $data['total_supplier']    = $this->Dashboard_model->totalSupplier();
        $data['total_komponen']    = $this->Dashboard_model->totalKomponen();
        $data['produksi']          = $this->Dashboard_model->produksiBerjalan();
        $data['qc']                = $this->Dashboard_model->qcBerjalan();
        $data['selesai']           = $this->Dashboard_model->produksiSelesai();
        $data['stok']              = $this->Dashboard_model->stokGudang();

        $data['produksi_terbaru']  = $this->Dashboard_model->produksiTerbaru();

        $this->template->load('main_template', 'pabrik/@dashboard', $data);
    }
}