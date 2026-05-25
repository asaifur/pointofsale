<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk_model extends CI_Model
{
    private $table = 'produk';
    private $kategori_table = 'kategori_produk';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // GET semua produk
    public function get_all()
    {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->kategori_table . ' k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.is_active', 1);
        $this->db->order_by('p.nama_produk', 'ASC');
        return $this->db->get()->result_array();
    }

    // GET produk by ID
    public function get_by_id($id_produk)
    {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->kategori_table . ' k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.id_produk', $id_produk);
        return $this->db->get()->row_array();
    }

    // GET produk by kode
    public function get_by_kode($kode_produk)
    {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->kategori_table . ' k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.kode_produk', $kode_produk);
        return $this->db->get()->row_array();
    }

    // Search produk
    public function search($keyword)
    {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->kategori_table . ' k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.is_active', 1);
        $this->db->group_start()
            ->like('p.nama_produk', $keyword)
            ->or_like('p.kode_produk', $keyword)
            ->or_like('k.nama_kategori', $keyword)
            ->group_end();
        $this->db->order_by('p.nama_produk', 'ASC');
        return $this->db->get()->result_array();
    }

    // GET produk by kategori
    public function get_by_kategori($id_kategori)
    {
        $this->db->select('p.*, k.nama_kategori');
        $this->db->from($this->table . ' p');
        $this->db->join($this->kategori_table . ' k', 'p.id_kategori = k.id_kategori');
        $this->db->where('p.id_kategori', $id_kategori);
        $this->db->where('p.is_active', 1);
        $this->db->order_by('p.nama_produk', 'ASC');
        return $this->db->get()->result_array();
    }

    // INSERT produk baru
    public function insert($data)
    {
        $insert_data = [
            'id_kategori' => $data['id_kategori'],
            'kode_produk' => $data['kode_produk'],
            'nama_produk' => $data['nama_produk'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'harga_beli' => $data['harga_beli'],
            'harga_jual' => $data['harga_jual'],
            'gambar' => $data['gambar'] ?? null,
            'is_active' => 1
        ];
        return $this->db->insert($this->table, $insert_data);
    }

    // UPDATE produk
    public function update($id_produk, $data)
    {
        return $this->db->where('id_produk', $id_produk)->update($this->table, $data);
    }

    // DELETE produk (soft delete)
    public function delete($id_produk)
    {
        return $this->db->where('id_produk', $id_produk)->update($this->table, ['is_active' => 0]);
    }

    // Cek stok produk
    public function get_stok($id_produk)
    {
        $this->db->select('stok_akhir');
        $this->db->from('stok_produk');
        $this->db->where('id_produk', $id_produk);
        $this->db->order_by('id_stok', 'DESC');
        $this->db->limit(1);
        $result = $this->db->get()->row_array();
        return $result ? $result['stok_akhir'] : 0;
    }

    // GET kategori
    public function get_kategori()
    {
        return $this->db->where('is_active', 1)->order_by('nama_kategori', 'ASC')->get($this->kategori_table)->result_array();
    }
}
