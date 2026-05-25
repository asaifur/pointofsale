<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Penjualan_model extends CI_Model
{
    private $table = 'penjualan';
    private $detail_table = 'detail_penjualan';
    private $pembayaran_table = 'pembayaran';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // GET semua penjualan
    public function get_all($limit = null, $offset = null)
    {
        $this->db->select('p.*, c.nama_pelanggan, u.email as user_email');
        $this->db->from($this->table . ' p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->join('users u', 'p.id_user = u.id', 'left');
        $this->db->order_by('p.tgl_penjualan', 'DESC');
        if ($limit) {
            $this->db->limit($limit, $offset ?? 0);
        }
        return $this->db->get()->result_array();
    }

    // GET penjualan by ID
    public function get_by_id($id_penjualan)
    {
        $this->db->select('p.*, c.nama_pelanggan, c.no_telp, c.alamat');
        $this->db->from($this->table . ' p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->where('p.id_penjualan', $id_penjualan);
        return $this->db->get()->row_array();
    }

    // GET penjualan by no transaksi
    public function get_by_no_transaksi($no_transaksi)
    {
        $this->db->select('p.*, c.nama_pelanggan');
        $this->db->from($this->table . ' p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->where('p.no_transaksi', $no_transaksi);
        return $this->db->get()->row_array();
    }

    // INSERT penjualan baru
    public function insert($data)
    {
        $insert_data = [
            'no_transaksi' => $data['no_transaksi'],
            'id_pelanggan' => $data['id_pelanggan'] ?? null,
            'id_user' => $data['id_user'],
            'total_items' => $data['total_items'] ?? 0,
            'subtotal' => $data['subtotal'] ?? 0,
            'diskon' => $data['diskon'] ?? 0,
            'pajak' => $data['pajak'] ?? 0,
            'total_harga' => $data['total_harga'] ?? 0,
            'status' => $data['status'] ?? 'pending',
            'metode_bayar' => $data['metode_bayar'] ?? 'tunai',
            'jumlah_bayar' => $data['jumlah_bayar'] ?? 0,
            'kembalian' => $data['kembalian'] ?? 0,
            'catatan' => $data['catatan'] ?? null
        ];
        $this->db->insert($this->table, $insert_data);
        return $this->db->insert_id();
    }

    // UPDATE penjualan
    public function update($id_penjualan, $data)
    {
        return $this->db->where('id_penjualan', $id_penjualan)->update($this->table, $data);
    }

    // INSERT detail penjualan
    public function insert_detail($id_penjualan, $id_produk, $qty, $harga_satuan, $diskon_item = 0)
    {
        $subtotal = ($qty * $harga_satuan) - $diskon_item;
        $data = [
            'id_penjualan' => $id_penjualan,
            'id_produk' => $id_produk,
            'qty' => $qty,
            'harga_satuan' => $harga_satuan,
            'diskon_item' => $diskon_item,
            'subtotal' => $subtotal
        ];
        return $this->db->insert($this->detail_table, $data);
    }

    // GET detail penjualan
    public function get_detail($id_penjualan)
    {
        $this->db->select('d.*, p.nama_produk, p.kode_produk');
        $this->db->from($this->detail_table . ' d');
        $this->db->join('produk p', 'd.id_produk = p.id_produk');
        $this->db->where('d.id_penjualan', $id_penjualan);
        return $this->db->get()->result_array();
    }

    // DELETE detail penjualan
    public function delete_detail($id_detail)
    {
        return $this->db->where('id_detail', $id_detail)->delete($this->detail_table);
    }

    // Generate nomor transaksi
    public function generate_no_transaksi()
    {
        $date = date('Ymd');
        $this->db->select_max('id_penjualan');
        $this->db->where("DATE_FORMAT(created_at, '%Y%m%d') =", $date);
        $query = $this->db->get($this->table);
        $row = $query->row();
        $seq = $row->id_penjualan ? str_pad($row->id_penjualan + 1, 4, '0', STR_PAD_LEFT) : '0001';
        return 'TRX' . $date . $seq;
    }

    // GET penjualan by tanggal
    public function get_by_date($date_from, $date_to = null)
    {
        if (!$date_to) {
            $date_to = $date_from;
        }
        $this->db->where("DATE(p.tgl_penjualan) >=", $date_from);
        $this->db->where("DATE(p.tgl_penjualan) <=", $date_to);
        $this->db->where('p.status', 'selesai');
        $this->db->select('p.*, c.nama_pelanggan');
        $this->db->from($this->table . ' p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->order_by('p.tgl_penjualan', 'DESC');
        return $this->db->get()->result_array();
    }

    // GET laporan penjualan
    public function get_laporan($date_from, $date_to = null)
    {
        if (!$date_to) {
            $date_to = $date_from;
        }

        $this->db->select("
            DATE(tgl_penjualan) as tgl,
            COUNT(*) as total_transaksi,
            SUM(subtotal) as total_penjualan,
            SUM(diskon) as total_diskon,
            SUM(pajak) as total_pajak,
            SUM(total_harga) as total_pendapatan
        ");
        $this->db->from($this->table);
        $this->db->where("DATE(tgl_penjualan) >=", $date_from);
        $this->db->where("DATE(tgl_penjualan) <=", $date_to);
        $this->db->where('status', 'selesai');
        $this->db->group_by("DATE(tgl_penjualan)");
        $this->db->order_by("DATE(tgl_penjualan)", 'DESC');
        return $this->db->get()->result_array();
    }

    // GET penjualan hari ini
    public function get_today()
    {
        $today = date('Y-m-d');
        $this->db->select('p.*, c.nama_pelanggan');
        $this->db->from($this->table . ' p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->where("DATE(p.tgl_penjualan)", $today);
        $this->db->where('p.status', 'selesai');
        $this->db->order_by('p.tgl_penjualan', 'DESC');
        return $this->db->get()->result_array();
    }

    // COUNT transaksi
    public function count_today()
    {
        $today = date('Y-m-d');
        $this->db->where("DATE(tgl_penjualan)", $today);
        $this->db->where('status', 'selesai');
        return $this->db->count_all_results($this->table);
    }

    // TOTAL pendapatan hari ini
    public function total_today()
    {
        $today = date('Y-m-d');
        $this->db->select_sum('total_harga');
        $this->db->where("DATE(tgl_penjualan)", $today);
        $this->db->where('status', 'selesai');
        $result = $this->db->get($this->table)->row_array();
        return $result['total_harga'] ?? 0;
    }
}
