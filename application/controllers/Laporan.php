<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Laporan extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Cek login
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Penjualan_model');
        $this->load->model('Stok_model');
    }

    // LAPORAN HARIAN
    public function harian()
    {
        $date = $this->input->get('date') ?? date('Y-m-d');

        $penjualan = $this->Penjualan_model->get_by_date($date);
        $laporan = $this->Penjualan_model->get_laporan($date);

        $data = [
            'title' => 'Laporan Penjualan Harian',
            'date' => $date,
            'penjualan' => $penjualan,
            'laporan' => $laporan
        ];

        $this->load->view('laporan/harian', $data);
    }

    // LAPORAN BULANAN
    public function bulanan()
    {
        $bulan = $this->input->get('bulan') ?? date('m');
        $tahun = $this->input->get('tahun') ?? date('Y');

        $date_from = $tahun . '-' . $bulan . '-01';
        $date_to = date('Y-m-t', strtotime($date_from));

        $laporan = $this->Penjualan_model->get_laporan($date_from, $date_to);

        $data = [
            'title' => 'Laporan Penjualan Bulanan',
            'bulan' => $bulan,
            'tahun' => $tahun,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'laporan' => $laporan
        ];

        $this->load->view('laporan/bulanan', $data);
    }

    // LAPORAN PERIODE
    public function periode()
    {
        $date_from = $this->input->get('date_from') ?? date('Y-m-d', strtotime('-7 days'));
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');

        $laporan = $this->Penjualan_model->get_laporan($date_from, $date_to);

        $data = [
            'title' => 'Laporan Penjualan Periode',
            'date_from' => $date_from,
            'date_to' => $date_to,
            'laporan' => $laporan
        ];

        $this->load->view('laporan/periode', $data);
    }

    // LAPORAN STOK
    public function stok()
    {
        $stok = $this->Stok_model->get_all();
        $stok_habis = $this->Stok_model->get_stok_habis();
        $nilai_stok = $this->Stok_model->get_nilai_stok();

        $data = [
            'title' => 'Laporan Stok Produk',
            'stok' => $stok,
            'stok_habis' => $stok_habis,
            'nilai_stok' => $nilai_stok
        ];

        $this->load->view('laporan/stok', $data);
    }

    // LAPORAN PRODUK BEST SELLER
    public function best_seller()
    {
        $date_from = $this->input->get('date_from') ?? date('Y-m-d', strtotime('-30 days'));
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');

        $this->db->select('
            p.id_produk,
            p.nama_produk,
            p.kode_produk,
            SUM(d.qty) as total_qty,
            SUM(d.subtotal) as total_revenue
        ');
        $this->db->from('detail_penjualan d');
        $this->db->join('produk p', 'd.id_produk = p.id_produk');
        $this->db->join('penjualan pj', 'd.id_penjualan = pj.id_penjualan');
        $this->db->where("DATE(pj.tgl_penjualan) >=", $date_from);
        $this->db->where("DATE(pj.tgl_penjualan) <=", $date_to);
        $this->db->where('pj.status', 'selesai');
        $this->db->group_by('d.id_produk');
        $this->db->order_by('total_qty', 'DESC');
        $this->db->limit(20);
        $best_seller = $this->db->get()->result_array();

        $data = [
            'title' => 'Laporan Produk Best Seller',
            'date_from' => $date_from,
            'date_to' => $date_to,
            'best_seller' => $best_seller
        ];

        $this->load->view('laporan/best_seller', $data);
    }

    // LAPORAN CUSTOMER
    public function customer()
    {
        $date_from = $this->input->get('date_from') ?? date('Y-m-d', strtotime('-30 days'));
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');

        $this->db->select('
            c.id_pelanggan,
            c.nama_pelanggan,
            COUNT(p.id_penjualan) as total_transaksi,
            SUM(p.total_harga) as total_belanja
        ');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan');
        $this->db->where("DATE(p.tgl_penjualan) >=", $date_from);
        $this->db->where("DATE(p.tgl_penjualan) <=", $date_to);
        $this->db->where('p.status', 'selesai');
        $this->db->where('p.id_pelanggan IS NOT NULL');
        $this->db->group_by('p.id_pelanggan');
        $this->db->order_by('total_belanja', 'DESC');
        $customer = $this->db->get()->result_array();

        $data = [
            'title' => 'Laporan Penjualan per Customer',
            'date_from' => $date_from,
            'date_to' => $date_to,
            'customer' => $customer
        ];

        $this->load->view('laporan/customer', $data);
    }

    // EXPORT KE CSV
    public function export_csv($type = 'harian')
    {
        $date_from = $this->input->get('date_from') ?? date('Y-m-d');
        $date_to = $this->input->get('date_to') ?? date('Y-m-d');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="laporan_' . $type . '_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        if ($type == 'harian' || $type == 'periode') {
            fputcsv($output, ['Tanggal', 'Transaksi', 'Penjualan', 'Diskon', 'Pajak', 'Pendapatan']);

            $laporan = $this->Penjualan_model->get_laporan($date_from, $date_to);
            foreach ($laporan as $row) {
                fputcsv($output, [
                    $row['tgl'],
                    $row['total_transaksi'],
                    number_format($row['total_penjualan'], 2, ',', '.'),
                    number_format($row['total_diskon'], 2, ',', '.'),
                    number_format($row['total_pajak'], 2, ',', '.'),
                    number_format($row['total_pendapatan'], 2, ',', '.')
                ]);
            }
        }

        fclose($output);
    }
}
