<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Cek login
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Produk_model');
        $this->load->library('form_validation');
    }

    // ========================================
    // READ - Daftar Produk
    // ========================================
    public function index()
    {
        $search = $this->input->get('search');
        $kategori_filter = $this->input->get('kategori');

        if ($search) {
            $produk = $this->Produk_model->search($search);
        } elseif ($kategori_filter) {
            $produk = $this->Produk_model->get_by_kategori($kategori_filter);
        } else {
            $produk = $this->Produk_model->get_all();
        }

        $data = [
            'title' => 'Manajemen Produk',
            'produk' => $produk,
            'kategori' => $this->Produk_model->get_kategori(),
            'search' => $search,
            'kategori_filter' => $kategori_filter
        ];

        $this->load->view('produk/index', $data);
    }

    // ========================================
    // CREATE - Tambah Produk (Form)
    // ========================================
    public function tambah()
    {
        $data = [
            'title' => 'Tambah Produk',
            'kategori' => $this->Produk_model->get_kategori(),
            'action' => 'tambah'
        ];

        $this->load->view('produk/form', $data);
    }

    // ========================================
    // CREATE - Simpan Produk Baru
    // ========================================
    public function simpan()
    {
        // Validasi
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('kode_produk', 'Kode Produk', 'required|is_unique[produk.kode_produk]');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|min_length[3]');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->tambah();
            return;
        }

        $data = [
            'id_kategori' => $this->input->post('id_kategori'),
            'kode_produk' => $this->input->post('kode_produk'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'harga_beli' => $this->input->post('harga_beli'),
            'harga_jual' => $this->input->post('harga_jual')
        ];

        if ($this->Produk_model->insert($data)) {
            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan');
            redirect('produk');
        } else {
            $this->session->set_flashdata('error', 'Gagal menambahkan produk');
            redirect('produk/tambah');
        }
    }

    // ========================================
    // UPDATE - Edit Produk (Form)
    // ========================================
    public function edit($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);

        if (!$produk) {
            show_404();
        }

        $data = [
            'title' => 'Edit Produk',
            'produk' => $produk,
            'kategori' => $this->Produk_model->get_kategori(),
            'action' => 'edit'
        ];

        $this->load->view('produk/form', $data);
    }

    // ========================================
    // UPDATE - Simpan Perubahan Produk
    // ========================================
    public function update($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);

        if (!$produk) {
            show_404();
        }

        // Validasi
        $this->form_validation->set_rules('id_kategori', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required|min_length[3]');
        $this->form_validation->set_rules('harga_beli', 'Harga Beli', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Harga Jual', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($id_produk);
            return;
        }

        $data = [
            'id_kategori' => $this->input->post('id_kategori'),
            'nama_produk' => $this->input->post('nama_produk'),
            'deskripsi' => $this->input->post('deskripsi'),
            'harga_beli' => $this->input->post('harga_beli'),
            'harga_jual' => $this->input->post('harga_jual')
        ];

        if ($this->Produk_model->update($id_produk, $data)) {
            $this->session->set_flashdata('success', 'Produk berhasil diperbarui');
            redirect('produk');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui produk');
            redirect('produk/edit/' . $id_produk);
        }
    }

    // ========================================
    // DELETE - Hapus Produk
    // ========================================
    public function hapus($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);

        if (!$produk) {
            show_404();
        }

        if ($this->Produk_model->delete($id_produk)) {
            $this->session->set_flashdata('success', 'Produk berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus produk');
        }

        redirect('produk');
    }

    // ========================================
    // DETAIL - Lihat Detail Produk
    // ========================================
    public function detail($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);

        if (!$produk) {
            show_404();
        }

        $data = [
            'title' => 'Detail Produk',
            'produk' => $produk
        ];

        $this->load->view('produk/detail', $data);
    }

    // ========================================
    // API - Get Produk Detail
    // ========================================
    public function get_detail($id_produk)
    {
        $produk = $this->Produk_model->get_by_id($id_produk);

        if ($produk) {
            echo json_encode([
                'success' => true,
                'data' => $produk
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Produk tidak ditemukan'
            ]);
        }
    }

    // ========================================
    // API - Check Kode Produk
    // ========================================
    public function check_kode($kode_produk)
    {
        $produk = $this->Produk_model->get_by_kode($kode_produk);

        if ($produk) {
            echo json_encode(['exists' => true]);
        } else {
            echo json_encode(['exists' => false]);
        }
    }
}
