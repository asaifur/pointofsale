<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pelanggan_model extends CI_Model
{
    private $table = 'pelanggan';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // GET semua pelanggan
    public function get_all()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('nama_pelanggan', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    // GET pelanggan by ID
    public function get_by_id($id_pelanggan)
    {
        return $this->db->where('id_pelanggan', $id_pelanggan)->get($this->table)->row_array();
    }

    // Search pelanggan
    public function search($keyword)
    {
        $this->db->where('is_active', 1);
        $this->db->group_start()
            ->like('nama_pelanggan', $keyword)
            ->or_like('no_telp', $keyword)
            ->or_like('email', $keyword)
            ->group_end();
        $this->db->order_by('nama_pelanggan', 'ASC');
        return $this->db->get($this->table)->result_array();
    }

    // INSERT pelanggan baru
    public function insert($data)
    {
        $insert_data = [
            'nama_pelanggan' => $data['nama_pelanggan'],
            'no_telp' => $data['no_telp'] ?? null,
            'email' => $data['email'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'kota' => $data['kota'] ?? null,
            'provinsi' => $data['provinsi'] ?? null,
            'kode_pos' => $data['kode_pos'] ?? null,
            'tipe_pelanggan' => $data['tipe_pelanggan'] ?? 'retail',
            'is_active' => 1
        ];
        return $this->db->insert($this->table, $insert_data);
    }

    // UPDATE pelanggan
    public function update($id_pelanggan, $data)
    {
        return $this->db->where('id_pelanggan', $id_pelanggan)->update($this->table, $data);
    }

    // DELETE pelanggan (soft delete)
    public function delete($id_pelanggan)
    {
        return $this->db->where('id_pelanggan', $id_pelanggan)->update($this->table, ['is_active' => 0]);
    }

    // GET pelanggan by tipe
    public function get_by_tipe($tipe_pelanggan)
    {
        return $this->db->where('is_active', 1)->where('tipe_pelanggan', $tipe_pelanggan)->get($this->table)->result_array();
    }

    // Count pelanggan
    public function count_all()
    {
        return $this->db->where('is_active', 1)->count_all_results($this->table);
    }
}
