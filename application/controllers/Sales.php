<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Sales extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Cek login
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Produk_model');
        $this->load->model('Pelanggan_model');
        $this->load->model('Penjualan_model');
        $this->load->model('Stok_model');
        $this->load->library('form_validation');
    }

    // HALAMAN KASIR UTAMA
    public function index()
    {
        $data = [
            'title' => 'Kasir - Point of Sale',
            'produk' => $this->Produk_model->get_all(),
            'pelanggan' => $this->Pelanggan_model->get_all()
        ];
        $this->load->view('sales/kasir', $data);
    }

    // HALAMAN DAFTAR TRANSAKSI
    public function daftar()
    {
        $per_page = 20;
        $page = $this->input->get('page') ?? 0;

        $data = [
            'title' => 'Daftar Transaksi Penjualan',
            'penjualan' => $this->Penjualan_model->get_all($per_page, $page),
            'page' => $page
        ];
        $this->load->view('sales/daftar', $data);
    }

    // HALAMAN DETAIL TRANSAKSI
    public function detail($id_penjualan)
    {
        $penjualan = $this->Penjualan_model->get_by_id($id_penjualan);
        if (!$penjualan) {
            show_404();
        }

        $detail = $this->Penjualan_model->get_detail($id_penjualan);

        $data = [
            'title' => 'Detail Transaksi',
            'penjualan' => $penjualan,
            'detail' => $detail
        ];
        $this->load->view('sales/detail', $data);
    }

    // API: SEARCH PRODUK
    public function search_produk()
    {
        $keyword = $this->input->get('q');
        $produk = $this->Produk_model->search($keyword);
        echo json_encode($produk);
    }

    // API: GET PRODUK DETAIL
    public function get_produk($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);
        $stok = $this->Stok_model->get_stok_produk($id_produk);

        $response = [
            'success' => true,
            'data' => $produk,
            'stok' => $stok['stok_akhir'] ?? 0
        ];
        echo json_encode($response);
    }

    // API: SEARCH PELANGGAN
    public function search_pelanggan()
    {
        $keyword = $this->input->get('q');
        $pelanggan = $this->Pelanggan_model->search($keyword);
        echo json_encode($pelanggan);
    }

    // API: BUAT TRANSAKSI BARU
    public function buat_transaksi()
    {
        $this->load->helper('url');

        $no_transaksi = $this->Penjualan_model->generate_no_transaksi();
        $user_id = $this->session->userdata('id');

        $data = [
            'no_transaksi' => $no_transaksi,
            'id_user' => $user_id,
            'id_pelanggan' => null,
            'status' => 'pending'
        ];

        $id_penjualan = $this->Penjualan_model->insert($data);

        $response = [
            'success' => true,
            'id_penjualan' => $id_penjualan,
            'no_transaksi' => $no_transaksi
        ];
        echo json_encode($response);
    }

    // API: TAMBAH ITEM KE TRANSAKSI
    public function tambah_item()
    {
        $post = $this->input->post();
        $id_penjualan = $post['id_penjualan'];
        $id_produk = $post['id_produk'];
        $qty = (int)$post['qty'];

        // Validasi stok
        $stok = $this->Stok_model->get_stok_produk($id_produk);
        $stok_tersedia = $stok['stok_akhir'] ?? 0;

        if ($qty > $stok_tersedia) {
            $response = [
                'success' => false,
                'message' => 'Stok tidak cukup. Tersedia: ' . $stok_tersedia
            ];
            echo json_encode($response);
            return;
        }

        // Ambil harga produk
        $produk = $this->Produk_model->get_by_id($id_produk);
        $harga_satuan = $produk['harga_jual'];

        // Insert detail penjualan
        $this->Penjualan_model->insert_detail($id_penjualan, $id_produk, $qty, $harga_satuan);

        // Update total
        $this->update_total_transaksi($id_penjualan);

        $response = [
            'success' => true,
            'message' => 'Item berhasil ditambahkan',
            'detail' => $this->Penjualan_model->get_detail($id_penjualan)
        ];
        echo json_encode($response);
    }

    // API: HAPUS ITEM DARI TRANSAKSI
    public function hapus_item()
    {
        $id_detail = $this->input->post('id_detail');
        $id_penjualan = $this->input->post('id_penjualan');

        $this->Penjualan_model->delete_detail($id_detail);
        $this->update_total_transaksi($id_penjualan);

        $response = [
            'success' => true,
            'message' => 'Item berhasil dihapus'
        ];
        echo json_encode($response);
    }

    // API: SIMPAN TRANSAKSI (CHECKOUT)
    public function simpan_transaksi()
    {
        $this->db->trans_start();

        $post = $this->input->post();
        $id_penjualan = $post['id_penjualan'];
        $id_pelanggan = !empty($post['id_pelanggan']) ? $post['id_pelanggan'] : null;
        $metode_bayar = $post['metode_bayar'] ?? 'tunai';
        $jumlah_bayar = floatval($post['jumlah_bayar'] ?? 0);

        // Get detail transaksi
        $detail = $this->Penjualan_model->get_detail($id_penjualan);

        // Calculate totals
        $total = 0;
        foreach ($detail as $item) {
            $total += $item['subtotal'];
        }

        $pajak = (int)$post['pajak'] ?? 0;
        $diskon = floatval($post['diskon'] ?? 0);
        $total_harga = $total - $diskon + $pajak;
        $kembalian = $jumlah_bayar - $total_harga;

        // Update penjualan
        $update_data = [
            'id_pelanggan' => $id_pelanggan,
            'total_items' => count($detail),
            'subtotal' => $total,
            'diskon' => $diskon,
            'pajak' => $pajak,
            'total_harga' => $total_harga,
            'status' => 'selesai',
            'metode_bayar' => $metode_bayar,
            'jumlah_bayar' => $jumlah_bayar,
            'kembalian' => $kembalian
        ];
        $this->Penjualan_model->update($id_penjualan, $update_data);

        // Update stok untuk setiap item
        foreach ($detail as $item) {
            $this->Stok_model->kurangi_stok($item['id_produk'], $item['qty'], 'Penjualan #' . $id_penjualan);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $response = [
                'success' => false,
                'message' => 'Gagal menyimpan transaksi'
            ];
        } else {
            $response = [
                'success' => true,
                'message' => 'Transaksi berhasil disimpan',
                'id_penjualan' => $id_penjualan
            ];
        }

        echo json_encode($response);
    }

    // FUNGSI: UPDATE TOTAL TRANSAKSI
    private function update_total_transaksi($id_penjualan)
    {
        $detail = $this->Penjualan_model->get_detail($id_penjualan);
        $total = 0;
        $count = 0;

        foreach ($detail as $item) {
            $total += $item['subtotal'];
            $count++;
        }

        $this->Penjualan_model->update($id_penjualan, [
            'total_items' => $count,
            'subtotal' => $total
        ]);
    }

    // CETAK STRUK
    public function cetak_struk($id_penjualan)
    {
        $penjualan = $this->Penjualan_model->get_by_id($id_penjualan);
        $detail = $this->Penjualan_model->get_detail($id_penjualan);

        $data = [
            'penjualan' => $penjualan,
            'detail' => $detail
        ];

        $this->load->view('sales/struk', $data);
    }

    // BATALKAN TRANSAKSI
    public function batalkan()
    {
        $id_penjualan = $this->input->post('id_penjualan');

        $penjualan = $this->Penjualan_model->get_by_id($id_penjualan);

        if ($penjualan['status'] == 'pending') {
            $this->Penjualan_model->update($id_penjualan, ['status' => 'batal']);

            $response = [
                'success' => true,
                'message' => 'Transaksi berhasil dibatalkan'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Hanya transaksi pending yang bisa dibatalkan'
            ];
        }

        echo json_encode($response);
    }
}
