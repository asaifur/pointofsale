<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_model extends CI_Model
{
    private $table = 'stok_produk';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // GET semua stok
    public function get_all()
    {
        $this->db->select('s.*, p.nama_produk, p.kode_produk, p.harga_beli, p.harga_jual');
        $this->db->from($this->table . ' s');
        $this->db->join('produk p', 's.id_produk = p.id_produk');
        $this->db->order_by('p.nama_produk', 'ASC');
        return $this->db->get()->result_array();
    }

    // GET stok by produk
    public function get_stok_produk($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $this->db->order_by('id_stok', 'DESC');
        $this->db->limit(1);
        return $this->db->get($this->table)->row_array();
    }

    // INSERT stok awal
    public function insert_stok_awal($id_produk, $stok_awal, $tgl_catat = null)
    {
        $data = [
            'id_produk' => $id_produk,
            'stok_awal' => $stok_awal,
            'stok_masuk' => 0,
            'stok_keluar' => 0,
            'stok_akhir' => $stok_awal,
            'tgl_catat' => $tgl_catat ?? date('Y-m-d'),
            'keterangan' => 'Stok awal'
        ];
        return $this->db->insert($this->table, $data);
    }

    // UPDATE stok masuk
    public function tambah_stok($id_produk, $qty, $keterangan = null)
    {
        // Ambil stok terakhir
        $stok_terakhir = $this->get_stok_produk($id_produk);
        $stok_akhir = ($stok_terakhir['stok_akhir'] ?? 0) + $qty;

        $data = [
            'id_produk' => $id_produk,
            'stok_awal' => $stok_terakhir['stok_akhir'] ?? 0,
            'stok_masuk' => $qty,
            'stok_keluar' => 0,
            'stok_akhir' => $stok_akhir,
            'tgl_catat' => date('Y-m-d'),
            'keterangan' => $keterangan ?? 'Penambahan stok'
        ];
        return $this->db->insert($this->table, $data);
    }

    // UPDATE stok keluar
    public function kurangi_stok($id_produk, $qty, $keterangan = null)
    {
        // Ambil stok terakhir
        $stok_terakhir = $this->get_stok_produk($id_produk);
        $stok_saat_ini = $stok_terakhir['stok_akhir'] ?? 0;

        // Cek stok cukup
        if ($stok_saat_ini < $qty) {
            return false;
        }

        $stok_akhir = $stok_saat_ini - $qty;

        $data = [
            'id_produk' => $id_produk,
            'stok_awal' => $stok_saat_ini,
            'stok_masuk' => 0,
            'stok_keluar' => $qty,
            'stok_akhir' => $stok_akhir,
            'tgl_catat' => date('Y-m-d'),
            'keterangan' => $keterangan ?? 'Penjualan'
        ];
        $this->db->insert($this->table, $data);
        return true;
    }

    // GET stok habis
    public function get_stok_habis()
    {
        $this->db->select('s.*, p.nama_produk, p.kode_produk');
        $this->db->from($this->table . ' s');
        $this->db->join('produk p', 's.id_produk = p.id_produk');
        $this->db->where('s.stok_akhir <=', 5);
        $this->db->order_by('s.stok_akhir', 'ASC');
        return $this->db->get()->result_array();
    }

    // GET nilai stok
    public function get_nilai_stok()
    {
        $this->db->select('SUM(s.stok_akhir * p.harga_beli) as nilai_stok');
        $this->db->from($this->table . ' s');
        $this->db->join('produk p', 's.id_produk = p.id_produk');
        $this->db->where('s.id_stok IN (SELECT MAX(id_stok) FROM ' . $this->table . ' GROUP BY id_produk)');
        $result = $this->db->get()->row_array();
        return $result['nilai_stok'] ?? 0;
    }

    // GET riwayat stok
    public function get_riwayat($id_produk)
    {
        $this->db->where('id_produk', $id_produk);
        $this->db->order_by('tgl_catat', 'DESC');
        $this->db->order_by('id_stok', 'DESC');
        $this->db->limit(30);
        return $this->db->get($this->table)->result_array();
    }
}
