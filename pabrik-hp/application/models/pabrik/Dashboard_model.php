<?php
class Dashboard_model extends CI_Model
{
    /**
     * Total produk di database
     * @return int
     * @development: Tambah filter produk aktif
     */
    function totalProduk()
    {
        return $this->db->count_all('produk_hp');
    }

    /**
     * Total karyawan di database
     * @return int
     * @development: Filter karyawan aktif
     */
    function totalKaryawan()
    {
        return $this->db->count_all('karyawan');
    }

    /**
     * Total supplier di database
     * @return int
     * @development: Filter supplier aktif
     */
    function totalSupplier()
    {
        return $this->db->count_all('supplier');
    }

    /**
     * Total komponen di database
     * @return int
     * @development: Filter komponen aktif
     */
    function totalKomponen()
    {
        return $this->db->count_all('komponen');
    }

    /**
     * Produksi status Perakitan
     * @return int
     * @development: Tambah filter tanggal
     */
    function produksiBerjalan()
    {
        return $this->db->where('status', 'Perakitan')
                        ->count_all_results('produksi');
    }

    /**
     * Produksi status QC
     * @return int
     * @development: Tambah filter tanggal
     */
    function qcBerjalan()
    {
        return $this->db->where('status', 'QC')
                        ->count_all_results('produksi');
    }

    /**
     * Produksi status Selesai
     * @return int
     * @development: Tambah filter tanggal
     */
    function produksiSelesai()
    {
        return $this->db->where('status', 'Selesai')
                        ->count_all_results('produksi');
    }

    /**
     * Total stok di gudang
     * @return int
     * @development: Tambah filter per produk atau lokasi
     */
    function stokGudang()
    {
        $this->db->select_sum('stok_produk');
        return $this->db->get('gudang')->row()->stok_produk;
    }

    /**
     * 5 data produksi terbaru dengan join produk & karyawan
     * @return object
     * @development: Tambah filter status atau tanggal
     */
    function produksiTerbaru()
    {
        $this->db->select('
            produksi.*,
            produk_hp.nama_produk,
            karyawan.nama_karyawan
        ')
        ->from('produksi')
        ->join('produk_hp', 'produk_hp.id_produk = produksi.id_produk')
        ->join('karyawan', 'karyawan.id_karyawan = produksi.id_karyawan')
        ->order_by('id_produksi', 'DESC')
        ->limit(5);

        return $this->db->get()->result();
    }
}