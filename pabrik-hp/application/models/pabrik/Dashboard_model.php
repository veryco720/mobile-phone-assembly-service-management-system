<?php
class Dashboard_model extends CI_Model
{

    function totalProduk()
    {
        return $this->db->count_all('produk_hp');
    }

    function totalKaryawan()
    {
        return $this->db->count_all('karyawan');
    }

    function totalSupplier()
    {
        return $this->db->count_all('supplier');
    }

    function totalKomponen()
    {
        return $this->db->count_all('komponen');
    }

    function produksiBerjalan()
    {
        return $this->db->where('status','Perakitan')
                        ->count_all_results('produksi');
    }

    function qcBerjalan()
    {
        return $this->db->where('status','QC')
                        ->count_all_results('produksi');
    }

    function produksiSelesai()
    {
        return $this->db->where('status','Selesai')
                        ->count_all_results('produksi');
    }

    function stokGudang()
    {
        $this->db->select_sum('stok_produk');
        return $this->db->get('gudang')->row()->stok_produk;
    }

    function produksiTerbaru()
    {
        $this->db->select('
            produksi.*,
            produk_hp.nama_produk,
            karyawan.nama_karyawan
        ');

        $this->db->from('produksi');

        $this->db->join('produk_hp',
        'produk_hp.id_produk=produksi.id_produk');

        $this->db->join('karyawan',
        'karyawan.id_karyawan=produksi.id_karyawan');

        $this->db->order_by('id_produksi','DESC');

        $this->db->limit(5);

        return $this->db->get()->result();
    }

}