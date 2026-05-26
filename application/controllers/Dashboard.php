<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // Proteksi: Cek session
        if (!$this->session->userdata('email')) {
            redirect('auth');
        }
        $this->load->model('Penjualan_model');
        $this->load->model('Produk_model');
        $this->load->model('Stok_model');
        $this->load->model('Pelanggan_model');
    }

    // ========================================
    // DASHBOARD UTAMA - POS ADMIN
    // ========================================
    public function index()
    {
        // KPI Today
        $today = date('Y-m-d');
        $total_transaksi_today = $this->Penjualan_model->count_today();
        $total_penjualan_today = $this->Penjualan_model->total_today();

        // Stok Data
        $stok_habis = count($this->Stok_model->get_stok_habis());
        $nilai_stok = $this->Stok_model->get_nilai_stok();

        // Best Sellers (Top 5)
        $best_sellers = $this->get_top_sellers();

        // Recent Transactions
        $recent_transactions = $this->get_recent_transactions();

        // This Month
        $date_from = date('Y-m-01');
        $date_to = date('Y-m-t');
        $this->db->select('SUM(total_harga) as total_bulan, COUNT(*) as trx_bulan');
        $this->db->from('penjualan');
        $this->db->where('status', 'selesai');
        $this->db->where("DATE(tgl_penjualan) >=", $date_from);
        $this->db->where("DATE(tgl_penjualan) <=", $date_to);
        $monthly = $this->db->get()->row_array();

        $data = [
            'title' => 'Dashboard Admin POS',
            'total_transaksi_today' => $total_transaksi_today,
            'total_penjualan_today' => $total_penjualan_today,
            'stok_habis' => $stok_habis,
            'nilai_stok' => $nilai_stok,
            'best_sellers' => $best_sellers,
            'recent_transactions' => $recent_transactions,
            'total_bulan' => $monthly['total_bulan'] ?? 0,
            'trx_bulan' => $monthly['trx_bulan'] ?? 0,
            'total_produk' => count($this->Produk_model->get_all()),
            'total_pelanggan' => $this->Pelanggan_model->count_all()
        ];

        $this->load->view('dashboard/index', $data);
    }

    // ========================================
    // API: GET CHART DATA
    // ========================================

    // Chart: Penjualan 7 Hari Terakhir
    public function chart_sales_7days()
    {
        $chart_data = array();
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' days'));
            $this->db->select_sum('total_harga');
            $this->db->from('penjualan');
            $this->db->where('status', 'selesai');
            $this->db->where('DATE(tgl_penjualan)', $date);
            $result = $this->db->get()->row_array();

            $chart_data[] = [
                'tanggal' => date('D, d M', strtotime($date)),
                'total' => floatval($result['total_harga'] ?? 0)
            ];
        }
        echo json_encode($chart_data);
    }

    // Chart: Penjualan per Kategori
    public function chart_sales_by_category()
    {
        $today = date('Y-m-d');
        $this->db->select('k.nama_kategori, SUM(d.subtotal) as total');
        $this->db->from('detail_penjualan d');
        $this->db->join('produk p', 'd.id_produk = p.id_produk');
        $this->db->join('kategori_produk k', 'p.id_kategori = k.id_kategori');
        $this->db->join('penjualan pj', 'd.id_penjualan = pj.id_penjualan');
        $this->db->where('DATE(pj.tgl_penjualan)', $today);
        $this->db->where('pj.status', 'selesai');
        $this->db->group_by('k.id_kategori');
        $this->db->order_by('total', 'DESC');

        $results = $this->db->get()->result_array();

        $chart_data = [
            'labels' => array_column($results, 'nama_kategori'),
            'data' => array_column($results, 'total')
        ];

        echo json_encode($chart_data);
    }

    // Chart: Top Products Today
    public function chart_top_products()
    {
        $today = date('Y-m-d');
        $this->db->select('p.nama_produk, SUM(d.qty) as total_qty, SUM(d.subtotal) as total');
        $this->db->from('detail_penjualan d');
        $this->db->join('produk p', 'd.id_produk = p.id_produk');
        $this->db->join('penjualan pj', 'd.id_penjualan = pj.id_penjualan');
        $this->db->where('DATE(pj.tgl_penjualan)', $today);
        $this->db->where('pj.status', 'selesai');
        $this->db->group_by('d.id_produk');
        $this->db->order_by('total_qty', 'DESC');
        $this->db->limit(5);

        $results = $this->db->get()->result_array();

        $chart_data = [
            'labels' => array_column($results, 'nama_produk'),
            'data' => array_column($results, 'total_qty')
        ];

        echo json_encode($chart_data);
    }

    // Chart: Hourly Sales
    public function chart_hourly_sales()
    {
        $today = date('Y-m-d');
        $hourly_data = array();

        for ($hour = 0; $hour < 24; $hour++) {
            $start_time = $today . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $end_time = $today . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':59:59';

            $this->db->select_sum('total_harga');
            $this->db->from('penjualan');
            $this->db->where('status', 'selesai');
            $this->db->where('tgl_penjualan >=', $start_time);
            $this->db->where('tgl_penjualan <=', $end_time);
            $result = $this->db->get()->row_array();

            $hourly_data[] = [
                'jam' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'total' => floatval($result['total_harga'] ?? 0)
            ];
        }

        echo json_encode($hourly_data);
    }

    // ========================================
    // HELPER FUNCTIONS
    // ========================================

    private function get_top_sellers()
    {
        $today = date('Y-m-d');
        $this->db->select('p.nama_produk, SUM(d.qty) as total_qty, SUM(d.subtotal) as total');
        $this->db->from('detail_penjualan d');
        $this->db->join('produk p', 'd.id_produk = p.id_produk');
        $this->db->join('penjualan pj', 'd.id_penjualan = pj.id_penjualan');
        $this->db->where('DATE(pj.tgl_penjualan)', $today);
        $this->db->where('pj.status', 'selesai');
        $this->db->group_by('d.id_produk');
        $this->db->order_by('total_qty', 'DESC');
        $this->db->limit(5);

        return $this->db->get()->result_array();
    }

    private function get_recent_transactions()
    {
        $this->db->select('p.id_penjualan, p.no_transaksi, p.total_harga, p.tgl_penjualan, 
                          c.nama_pelanggan, p.metode_bayar, p.status');
        $this->db->from('penjualan p');
        $this->db->join('pelanggan c', 'p.id_pelanggan = c.id_pelanggan', 'left');
        $this->db->order_by('p.tgl_penjualan', 'DESC');
        $this->db->limit(10);

        return $this->db->get()->result_array();
    }

    public function chartPemilikUsahaBulanIni()
    {
        $bulan = date('m');
        $tahun = date('Y');

        $this->db->select("DATE(created_at) as tanggal, COUNT(*) as total");
        $this->db->from("pemilikusaha");
        $this->db->where("MONTH(created_at)", $bulan);
        $this->db->where("YEAR(created_at)", $tahun);
        $this->db->group_by("DATE(created_at)");
        $this->db->order_by("DATE(created_at)", "ASC");

        $query = $this->db->get()->result();

        echo json_encode($query);
    }

    public function roleProses()
    {

        $data = ['title' => "Data Master Akses"];
        $this->template->load('adminlte/daftarMasterAkses', $data);
    }


    public function qc1()
    {

        $data = ['title' => "Data Quality Control 1 (FOTO)"];
        $this->template->load('adminlte/qc1', $data);
    }
    public function qc2()
    {

        $data = ['title' => "Data Quality Control 2 (Email)"];
        $this->template->load('adminlte/qc2', $data);
    }
    public function qc3()
    {

        $data = ['title' => "Data Quality Control 3 (OSS)"];
        $this->template->load('adminlte/qc3', $data);
    }
    public function qc4()
    {

        $data = ['title' => "Data Quality Control 4 (NIB)"];
        $this->template->load('adminlte/qc4', $data);
    }
    public function qc5()
    {

        $data = ['title' => "Data Quality Control 5 (STTD)"];
        $this->template->load('adminlte/qc5', $data);
    }
    public function qc6()
    {

        $data = ['title' => "Data Quality Control 6 (SH)"];
        $this->template->load('adminlte/qc6', $data);
    }
    public function p3h()
    {
        $data = ['title' => "Data Harian P3H"];
        $this->template->load('adminlte/daftarPU', $data);
    }

    public function formTambahPU($action, $id = null)
    {
        $data = [
            'action' => $action,
            'format' => $this->Halal_model->format_action('format_tambah_pu', $action)->result(),
            'provinsi' => $this->Halal_model->getProvinsi(),
        ];
        if ($id) {
            $data['dtkolom'] = $this->Halal_model->fetch_data('pemilikusaha', ['id' => $id])->result();
        }
        $this->load->view('adminlte/modalDaftarPU', $data);
    }

    public function view_daftar_pu()
    {
        echo $this->Halal_model->get_daftar_pu();
    }


    public function view_daftar_qc_foto()
    {
        echo $this->Halal_model->get_daftar_qc_1();
    }
    public function view_daftar_qc_2()
    {
        echo $this->Halal_model->get_daftar_qc_2();
    }
    public function view_daftar_qc_3()
    {
        echo $this->Halal_model->get_daftar_qc_3();
    }
    public function view_daftar_qc_4()
    {
        echo $this->Halal_model->get_daftar_qc_4();
    }
    public function view_daftar_qc_5()
    {
        echo $this->Halal_model->get_daftar_qc_5();
    }
    public function view_daftar_qc_6()
    {
        echo $this->Halal_model->get_daftar_qc_6();
    }



    public function update_qc_foto()
    {
        // Pastikan request AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        // Ambil input POST
        $id        = $this->input->post('id', TRUE);
        $qc_status = $this->input->post('qc_status', TRUE);

        // Validasi sederhana
        if (empty($id)) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak ditemukan'
            ]);
            return;
        }

        // Data update
        $dataUpdate = [
            'qc_foto'   => $qc_status,
            'update_created_date_qc_foto'     => date('Y-m-d H:i:s'),
            'update_created_by_qc_foto'       => $this->session->userdata('email') // atau id user login
        ];

        // Update ke database (ganti nama tabel sesuai tabel kamu)
        $this->db->where('id', $id);
        $update = $this->db->update('pemilikusaha', $dataUpdate);

        if ($update) {
            echo json_encode([
                'success' => true
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Gagal update database'
            ]);
        }
    }

    public function getKabupaten()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Halal_model->getKabupaten($id));
    }

    public function getKecamatan()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Halal_model->getKecamatan($id));
    }

    public function getKelurahan()
    {
        $id = $this->input->post('id');
        echo json_encode($this->Halal_model->getKelurahan($id));
    }
    public function halal()

    {

        // Cek session agar tidak bisa diakses tanpa login

        if (!$this->session->userdata('role')) {

            redirect('auth');
        }



        // Gunakan ARRAY untuk $data

        $data = [

            'title' => 'Daftar Jobdesk Entry Data ',

            'subtitle' => 'Selamat Datang'

        ];



        // Panggil library template

        $this->template->load('adminlte/halal', $data);
    }





    public function view_job()

    {

        $query = $this->Halal_model->Transaksi_NIB();

        echo $query;
    }

    public function view_nib()

    {

        $table = "data_query"; // Nama Tabel

        $column_order = array(null, 'nib', 'source_image', 'created_at'); // Untuk sorting

        $column_search = array('nib', 'source_image'); // Untuk searching

        $order = array('id' => 'asc'); // Default order



        $list = $this->datatables_ssp->get_table($table, $column_order, $column_search, $order);

        $data = array();

        $no = $_POST['start'];



        foreach ($list as $field) {

            $no++;

            $row = array();

            $row['id_kategori'] = $field->id; // Untuk No (meta.row di JS)

            $row['nama_kategori'] = $field->nib;

            $row['subkat'] = $field->source_image;

            $row['created_at'] = $field->created_at;



            $data[] = $row;
        }



        $output = array(

            "draw" => $_POST['draw'],

            "recordsTotal" => $this->datatables_ssp->count_all($table),

            "recordsFiltered" => $this->datatables_ssp->count_filtered($table, $column_order, $column_search, $order),

            "data" => $data,

        );

        echo json_encode($output);
    }



    function addJobdesk($action, $id = null)

    {

        $data['action'] = $action;
        $data['format'] = $this->Halal_model->format_action('format_jobdesk', $action)->result();

        if ($action <> "insert") {
            $id = $this->input->post('id');
            $data['dtKolom'] = $this->Halal_model->fetch_data('jobdesk', ['id' => $id])->row();
        }
        $this->load->view('adminlte/modal_tambah', $data);
    }



    public function insert_nib()
    {
        $action = $this->input->post('action');


        $data = [];
        $format = $this->Halal_model->format_action('format_jobdesk', $action)->result();
        foreach ($format as $kolom) {

            // FILE
            if ($kolom->code == "source_image") {
                $config['upload_path']   = './assets/uploads/';
                $config['allowed_types'] = 'pdf|jpg|png|jpeg';
                $config['max_size']      = 2048; // 2MB
                $config['file_name']     = 'NIB-' . time();

                $this->load->library('upload', $config);

                $file_name = '';

                if (!empty($_FILES['file']['name'])) {
                    if ($this->upload->do_upload('file')) {
                        $file_name = $this->upload->data('file_name');
                    } else {
                        echo json_encode([
                            'status'  => false,
                            'message' => $this->upload->display_errors()
                        ]);
                        return;
                    }
                }
                $data[$kolom->code] = $file_name;
            }

            // USER LOGIN
            else if ($kolom->code == "user") {
                $data[$kolom->code] = $_SESSION['email'];
            } else if ($kolom->code == "created_at") {
                $data[$kolom->code] = date('Y-m-d H:i:s');
            } else if ($kolom->code == "qc") {
                $data[$kolom->code] = 0;
            } else if ($kolom->code == "id_referal") {
                $data[$kolom->code] = $_SESSION['referal'];
            }

            // INPUT BIASA
            else {
                $data[$kolom->code] = htmlspecialchars(
                    $this->input->post($kolom->code),
                    ENT_QUOTES
                );
            }
        }
        if ($action == "insert") {

            $proses = $this->Halal_model->insertData('jobdesk', $data);
        } else if ($action == "update") {

            $where = ['id' => $this->input->post('id')];

            $proses = $this->Halal_model->updateData('jobdesk', $data, $where);
        } else if ($action == "delete") {

            $where = ['id' => $this->input->post('id')];

            $proses = $this->Halal_model->deleteData('jobdesk', $where);
        }
        if ($proses) {
            echo json_encode([
                'status'  => true,
                'message' => 'Data berhasil diproses'
            ]);
        } else {
            echo json_encode([
                'status'  => false,
                'message' => 'Gagal memproses data'
            ]);
        }
    }




    public function check_nib_exists()

    {

        $nib = $this->input->post('nib');



        // Sesuaikan 'tabel_nib' dengan nama tabel Anda

        $this->db->where('nib', $nib);

        $query = $this->db->get('jobdesk');



        if ($query->num_rows() > 0) {

            echo json_encode(['exists' => true]);
        } else {

            echo json_encode(['exists' => false]);
        }
    }

    public function delete_nib()

    {

        $id = $this->input->post('id');



        // 1. Ambil data untuk mendapatkan nama file gambar (opsional: jika ingin hapus file juga)

        $data = $this->db->get_where('jobdesk', ['id' => $id])->row();



        if ($data) {

            // Hapus file fisik dari folder jika ada

            if ($data->source_image != '' && file_exists('./assets/uploads/' . $data->source_image)) {

                unlink('./assets/uploads/' . $data->source_image);
            }



            // 2. Hapus data dari database

            $delete = $this->db->delete('jobdesk', ['id' => $id]);



            if ($delete) {

                echo json_encode(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            } else {

                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data database']);
            }
        } else {

            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }





    function Sppl()

    {

        if (!$this->session->userdata('role')) {

            redirect('auth');
        }



        // Gunakan ARRAY untuk $data

        $data = [

            'title' => 'Daftar SPPL Entry Data ',

            'subtitle' => 'Selamat Datang'

        ];



        // Panggil library template

        $this->template->load('adminlte/sppl', $data);
    }



    public function view_sppl_json()

    {

        header('Content-Type: application/json');

        echo $this->Halal_model->get_sppl_datatables();
    }

    public function simpan_sppl()

    {

        // 1. Konfigurasi Upload

        $config['upload_path'] = './assets/uploads/ttd/';

        $config['allowed_types'] = 'jpg|jpeg|png';

        $config['max_size'] = 200; // 200 KB sesuai permintaan

        $config['file_name'] = 'ttd_' . time();



        $this->load->library('upload', $config);



        $nama_file_ttd = '';



        if (!$this->upload->do_upload('source_ttd')) {

            // Jika gagal upload, kirim pesan error dari library CI

            $error = $this->upload->display_errors('', '');

            echo json_encode(['status' => 'error', 'message' => 'Upload Gagal: ' . $error]);

            return;
        } else {

            $upload_data = $this->upload->data();

            $nama_file_ttd = $upload_data['file_name'];



            // 2. Konfigurasi Resize paksa ke 100x100 px

            $config_resize['image_library'] = 'gd2';

            $config_resize['source_image'] = './assets/uploads/ttd/' . $nama_file_ttd;

            $config_resize['maintain_ratio'] = FALSE; // FALSE agar benar-benar 100x100

            $config_resize['width'] = 100;

            $config_resize['height'] = 100;



            $this->load->library('image_lib', $config_resize);

            if (!$this->image_lib->resize()) {

                echo json_encode(['status' => 'error', 'message' => 'Gagal resize: ' . $this->image_lib->display_errors()]);

                return;
            }
        }



        // 3. Masukkan ke Database

        $data = [

            'no_sppl' => 'sppl_' . time(),

            'namaPU' => $this->input->post('namaPU'),

            'noTelepon' => $this->input->post('noTelepon'),

            'created_date' => date('Y-m-d H:i:s'),

            'created_by' => $this->session->userdata('email'),

            'noNIB' => $this->input->post('noNIB'),

            'alamat' => $this->input->post('alamat'),

            'email' => $this->input->post('email'),

            'kode_kbli' => $this->input->post('kode_kbli'),

            'judul_kbli' => $this->input->post('judul_kbli'),

            'nomorNKU' => $this->input->post('nomorNKU'),

            'kota' => $this->input->post('kota'),

            'source_ttd' => $nama_file_ttd

        ];

        $insert = $this->db->insert('sppl', $data);



        if ($insert) {

            echo json_encode(['status' => 'success', 'message' => 'Data dan TTD berhasil disimpan']);
        } else {

            echo json_encode(['status' => 'error', 'message' => 'Gagal simpan ke database']);
        }
    }



    public function delete_sppl($id)

    {

        // 1. Cari data berdasarkan ID untuk mendapatkan nama file TTD

        $data = $this->db->get_where('sppl', ['id' => $id])->row();



        if ($data) {

            // 2. Hapus file fisik dari folder jika ada

            $path_file = './assets/uploads/ttd/' . $data->source_ttd;

            if (!empty($data->source_ttd) && file_exists($path_file)) {

                unlink($path_file);
            }



            // 3. Hapus data dari database

            $delete = $this->db->delete('sppl', ['id' => $id]);



            if ($delete) {

                echo json_encode(['status' => 'success', 'message' => 'Data dan file berhasil dihapus']);
            } else {

                echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data dari database']);
            }
        } else {

            echo json_encode(['status' => 'error', 'message' => 'Data tidak ditemukan']);
        }
    }



    public function print_sppl($id)

    {

        // 1. Ambil data dari database berdasarkan ID

        $data_sppl = $this->db->get_where('sppl', ['id' => $id])->row_array();



        if (!$data_sppl) {

            show_404();
        }



        // 2. Load Library Dompdf

        // Jika pakai composer cukup: use Dompdf\Dompdf;

        // Jika library diletakkan manual, sesuaikan cara load-nya

        $dompdf = new \Dompdf\Dompdf();



        // 3. Siapkan HTML dari view (pisahkan file view agar rapi)

        // Kita kirim data ke view 'print/v_sppl_pdf'

        $html = $this->load->view('adminlte/sppl_draft', $data_sppl, true);



        // 4. Konfigurasi Dompdf

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portrait');

        $dompdf->render();



        // 5. Output ke browser (Inline: tampil di browser, Attachment: langsung download)

        $dompdf->stream("SPPL_" . $data_sppl['no_sppl'] . ".pdf", array("Attachment" => false));
    }



    public function chartJobdesk()

    {

        $email = $this->session->userdata('email');

        $this->db->select('DATE(created_at) AS tanggal, COUNT(*) AS total');

        $this->db->from('jobdesk');

        $this->db->where('user', $email);

        $this->db->where('qc', 1);

        $this->db->group_by('DATE(created_at)');

        $this->db->order_by('DATE(created_at)', 'ASC');



        $query = $this->db->get();



        echo json_encode($query->result());
    }

    public function editJobdesk()

    {

        $id = $this->input->post('id');

        $data['job'] = $this->Halal_model->getById($id);

        $this->load->view('adminlte/form_edit_jobdesk', $data);
    }



    public function updateJobdesk()

    {



        $id = $this->input->post('id');



        // Data input

        // ==========================

        if (!empty($_FILES['source_image']['name'])) {



            $config['upload_path']   = './assets/uploads/';

            $config['allowed_types'] = 'jpg|jpeg|png';

            $config['max_size']      = 2048; // 2MB

            $config['encrypt_name']  = true;



            $this->load->library('upload', $config);



            if (!$this->upload->do_upload('source_image')) {

                echo json_encode([

                    'status'  => 'error',

                    'message' => $this->upload->display_errors('', '')

                ]);

                return;
            }




            // Upload berhasil

            $uploadData = $this->upload->data();

            $data['source_image'] = $uploadData['file_name'];



            // Hapus gambar lama

            $oldImage = $this->input->post('old_image');

            if (!empty($oldImage) && file_exists('./assets/uploads/' . $oldImage)) {

                unlink('./assets/uploads/' . $oldImage);
            }
        }



        // ==========================

        // UPDATE DATABASE

        // ==========================

        $this->db->where('id', $id);

        $update = $this->db->update('jobdesk', $data);



        if ($update) {

            echo json_encode([

                'status'  => 'success',

                'message' => 'Data jobdesk berhasil diperbarui'

            ]);
        } else {

            echo json_encode([

                'status'  => 'error',

                'message' => 'Gagal memperbarui data'

            ]);
        }
    }

    public function simpanPU($action)
    {
        if ($this->input->method() !== 'post') {
            echo json_encode([
                'status' => false,
                'message' => 'Method tidak diizinkan'
            ]);
            return;
        }

        $format = $this->Halal_model->format_action('format_tambah_pu', $action)->result();

        $data = [];

        // =========================
        // SET PATH UPLOAD
        // =========================
        $path = FCPATH . 'assets/uploads/';

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $config = [
            'upload_path'   => $path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 2048,
            'encrypt_name'  => true
        ];

        $this->load->library('upload');

        // =========================
        // LOOP FORMAT
        // =========================
        foreach ($format as $kolom) {

            // DATE AUTO
            if ($kolom->type == "DATE") {

                $data[$kolom->code] = date('Y-m-d H:i:s');
            }

            // USER SESSION
            elseif ($kolom->type == "user") {

                $data[$kolom->code] = $_SESSION['email'];
            }

            // FILE UPLOAD
            elseif ($kolom->type == "file") {

                $field = $kolom->code;

                if (!empty($_FILES[$field]['name'])) {

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload($field)) {

                        $uploadData = $this->upload->data();

                        $data[$field] = $uploadData['file_name'];

                        // =========================
                        // COMPRESS 70%
                        // =========================
                        $this->compressImage($uploadData['full_path']);
                    } else {

                        echo json_encode([
                            'status'  => false,
                            'message' => $this->upload
                                ->display_errors('', '')
                        ]);
                        return;
                    }
                }
            }

            // INPUT BIASA
            else {

                $data[$kolom->code] = htmlspecialchars($this->input->post($kolom->code), ENT_QUOTES);
            }
        }
        if ($action == "insert") {

            $insert = $this->db->insert('pemilikusaha', $data);

            $res = $insert
                ? ['status' => true, 'message' => 'Data berhasil disimpan']
                : ['status' => false, 'message' => 'Gagal menyimpan data'];
        } elseif ($action == "update") {

            $id = $this->input->post('id');

            $this->db->where('id', $id);
            $update = $this->db->update('pemilikusaha', $data);

            $res = $update
                ? ['status' => true, 'message' => 'Data berhasil diupdate']
                : ['status' => false, 'message' => 'Gagal update data'];
        } else {

            $res = [
                'status'  => false,
                'message' => 'Action tidak dikenali'
            ];
        }

        echo json_encode($res);
    }
    private function compressImage($filePath)
    {
        $config['image_library']  = 'gd2';
        $config['source_image']  = $filePath;
        $config['create_thumb']  = false;
        $config['maintain_ratio'] = true;
        $config['quality']       = '70%'; // 🔥 Compress 70%
        $config['width']         = 0;     // tidak resize
        $config['height']        = 0;

        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        $this->image_lib->clear();
    }
    public function form_update_qc($action)
    {
        $id = $this->input->post('id');

        $data['row'] = $this->db->get_where('pemilikusaha', ['id' => $id])->row();
        $data['format'] = $this->Halal_model->format_action('format_tambah_pu', $action)->result();
        $data['action'] = $action;
        $data['keterangan'] = $this->Halal_model->fetch_data('keterangan_pengajuan')->result();
        $this->load->view('adminlte/form_update_qc', $data);
    }

    public function update_qc_detail($action)
    {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $id = $this->input->post('id', TRUE);

        if (!$id) {
            echo json_encode([
                'success' => false,
                'message' => 'ID tidak ditemukan'
            ]);
            return;
        }

        $data = [];
        $format = $this->Halal_model->format_action('format_tambah_pu', $action)->result();
        if ($action == "update_qc_email") {

            foreach ($format as $kolom) {
                if ($kolom->code == "qc_email") {
                    $data[$kolom->code] = '1';
                } else if ($kolom->code == "update_created_date_qc_email") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else if ($kolom->code == "update_created_by_qc_email") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
            $data['qc_oss'] = '0';
        }
        if ($action == "update_qc_foto") {
            foreach ($format as $kolom) {
                if ($kolom->code == "update_created_by_qc_foto") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else if ($kolom->code == "update_created_date_qc_foto") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
            $data['qc_email'] = '0';
        }
        if ($action == "update_qc_oss") {
            foreach ($format as $kolom) {
                if ($kolom->code == "update_user_qc_oss") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else if ($kolom->code == "update_waktu_qc_oss") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
            $data['qc_nib'] = '0';
        }
        if ($action == "update_qc_nib") {
            foreach ($format as $kolom) {
                if ($kolom->code == "update_created_by_qc_nib") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else if ($kolom->code == "update_created_date_qc_nib") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
            $data['qc_sttd'] = '0';
        }
        if ($action == "update_qc_sttd") {
            foreach ($format as $kolom) {
                if ($kolom->code == "update_created_date_qc_sttd") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else if ($kolom->code == "update_created_by_qc_sttd") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
            $data['qc_sh'] = '0';
        }
        if ($action == "update_qc_sh") {
            foreach ($format as $kolom) {



                if ($kolom->code == "update_created_by_qc_sh") {
                    $data[$kolom->code] = $this->session->userdata('email');
                } else if ($kolom->type == "FILE") {

                    if (!empty($_FILES[$kolom->code]['name'])) {

                        $config['upload_path']   = './assets/uploads/';
                        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                        $config['max_size']      = 1024; // 50MB kalau mp4
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload');
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload($kolom->code)) {

                            echo json_encode([
                                'status' => 'error',
                                'message' => strip_tags($this->upload->display_errors())
                            ]);
                            return;
                        }

                        $data[$kolom->code] = $this->upload->data('file_name');
                    }
                } else if ($kolom->code == "update_created_date_qc_sh") {
                    $data[$kolom->code] = date("Y-m-d H:i:s");
                } else {
                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
                $data['qc_sh'] = '1';
            }
        }
        if ($action == "update") {

            // Ambil data lama dulu
            $oldData = $this->db->where('id', $id)
                ->get('pemilikusaha')
                ->row();

            foreach ($format as $kolom) {

                // ===============================
                // HANDLE FILE (JPG, PNG, PDF)
                // ===============================
                if ($kolom->type == "FILE") {

                    if (!empty($_FILES[$kolom->code]['name'])) {

                        $config['upload_path']   = './assets/uploads/';
                        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                        $config['max_size']      = 2048;
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload');
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload($kolom->code)) {
                            echo json_encode([
                                'success' => false,
                                'message' => strip_tags($this->upload->display_errors())
                            ]);
                            return;
                        }

                        // Hapus file lama (optional, kalau mau)
                        if (
                            !empty($oldData->{$kolom->code}) &&
                            file_exists('./assets/uploads/' . $oldData->{$kolom->code})
                        ) {
                            unlink('./assets/uploads/' . $oldData->{$kolom->code});
                        }

                        $data[$kolom->code] = $this->upload->data('file_name');
                    } else {
                        // Tidak upload baru → pakai data lama
                        $data[$kolom->code] = $oldData->{$kolom->code};
                    }

                    // ===============================
                    // HANDLE VIDEO MP4
                    // ===============================
                } else if ($kolom->type == "FILEMP4") {

                    if (!empty($_FILES[$kolom->code]['name'])) {

                        $config['upload_path']   = './assets/uploads/';
                        $config['allowed_types'] = 'mp4|mov|H.264|| MPEG-4|M4V';
                        $config['max_size']      = 51200; // 50MB
                        $config['encrypt_name']  = TRUE;

                        $this->load->library('upload');
                        $this->upload->initialize($config);

                        if (!$this->upload->do_upload($kolom->code)) {
                            echo json_encode([
                                'success' => false,
                                'message' => strip_tags($this->upload->display_errors())
                            ]);
                            return;
                        }

                        // Hapus video lama
                        if (
                            !empty($oldData->{$kolom->code}) &&
                            file_exists('./assets/uploads/' . $oldData->{$kolom->code})
                        ) {
                            unlink('./assets/uploads/' . $oldData->{$kolom->code});
                        }

                        $data[$kolom->code] = $this->upload->data('file_name');
                    } else {
                        // Tidak upload baru → pakai video lama
                        $data[$kolom->code] = $oldData->{$kolom->code};
                    }

                    // ===============================
                    // FIELD BIASA
                    // ===============================
                } else {

                    $data[$kolom->code] = $this->input->post($kolom->code);
                }
            }
        }

        $this->db->where('id', $id);
        $update = $this->db->update('pemilikusaha', $data);

        echo json_encode([
            'success' => $update ? true : false,
            'message' => $update ? '' : 'Gagal update database'
        ]);
    }
    public function aksi_button($id, $qc_foto, $update_by)
    {
        $btn = '
        <button type="button" class="btn btn-sm btn-info btn-view" data-id="' . $id . '">
            <i class="fas fa-eye"></i> Detail
        </button>
    ';

        if ($qc_foto == 0 && !empty($update_by)) {
            $btn .= '
            <button type="button" class="btn btn-sm btn-warning btn-update" data-id="' . $id . '">
                <i class="fa fa-pencil-alt"></i> Update PU
            </button>
        ';
        }

        return $btn;
    }
    public function gajiTable()
    {
        $bulan = $this->input->get('bulan');

        $this->db->select('
    users.username,
    users.email,
    COUNT(jobdesk.id) as total_jobdesk
');
        $this->db->from('jobdesk');
        $this->db->join('users', 'jobdesk.user = users.email');
        $this->db->where('jobdesk.qc', 1);

        if (!empty($bulan)) {

            $explode = explode('-', $bulan);
            $year  = $explode[0];
            $month = $explode[1];

            $this->db->where('MONTH(jobdesk.created_at)', $month);
            $this->db->where('YEAR(jobdesk.created_at)', $year);
        }

        $this->db->group_by('users.email');
        $this->db->order_by('total_jobdesk', 'DESC');

        $data['result'] = $this->db->get()->result();

        $this->load->view('adminlte/gaji_table', $data);
    }
    public function gajiTablekaryawan()
    {
        $bulan = $this->input->get('bulan');
        $user = $this->session->userdata();
        $email = $user['email'];
        $this->db->select('
    users.username,
    users.email,
    COUNT(jobdesk.id) as total_jobdesk
');
        $this->db->from('jobdesk');
        $this->db->join('users', 'jobdesk.user = users.email');
        $this->db->where('jobdesk.qc', 1);

        if (!empty($bulan)) {

            $explode = explode('-', $bulan);
            $year  = $explode[0];
            $month = $explode[1];

            $this->db->where('MONTH(jobdesk.created_at)', $month);
            $this->db->where('YEAR(jobdesk.created_at)', $year);
        }

        $this->db->where('users.email', $email);

        $data['result'] = $this->db->get()->row();
        $this->load->view('adminlte/gaji_table_karyawan', $data);
    }
    public function view_all_user_access()
    {
        echo $this->User_model->view_all_user_access();
    }
    public function view_daftar_qc_by_foto()
    {
        echo $this->Halal_model->view_daftar_qc_by_foto();
    }
}
