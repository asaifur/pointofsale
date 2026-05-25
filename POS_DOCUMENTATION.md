# DOKUMENTASI SISTEM POS (Point of Sale) - CodeIgniter 3

## Daftar Isi

1. [Instalasi](#instalasi)
2. [Struktur Database](#struktur-database)
3. [Fitur Utama](#fitur-utama)
4. [Panduan Penggunaan](#panduan-penggunaan)
5. [API Reference](#api-reference)
6. [Troubleshooting](#troubleshooting)

---

## Instalasi

### Prasyarat

- PHP 7.0 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- CodeIgniter 3.x
- XAMPP/WAMP/LAMP Server

### Langkah-Langkah Instalasi

#### 1. Setup Database

```sql
-- Import file pos_system.sql ke database Anda
mysql -u root -p database_name < pos_system.sql
```

Atau gunakan phpMyAdmin:

1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru
3. Import file `pos_system.sql`

#### 2. Konfigurasi Database CodeIgniter

Edit file `application/config/database.php`:

```php
$db['default'] = array(
    'dsn'	=> '',
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'nama_database',
    'dbdriver' => 'mysqli',
    'dbprefix' => '',
    'pconnect' => FALSE,
    'db_debug' => (ENVIRONMENT !== 'production'),
    'cache_on' => FALSE,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'encrypt' => FALSE,
    'compress' => FALSE,
    'stricton' => FALSE,
    'failover' => array(),
    'save_queries' => TRUE
);
```

#### 3. Load Database di Autoload

Edit file `application/config/autoload.php`:

```php
$autoload['libraries'] = array('database', 'session');
$autoload['models'] = array();
$autoload['helper'] = array('url', 'form');
```

#### 4. Restart Server

Akses aplikasi: `http://localhost/pointofsale`

---

## Struktur Database

### Tabel-Tabel Utama

#### 1. **kategori_produk**

Menyimpan kategori produk

```
- id_kategori (PK)
- nama_kategori
- deskripsi
- is_active
- created_at, updated_at
```

#### 2. **produk**

Menyimpan data produk

```
- id_produk (PK)
- id_kategori (FK)
- kode_produk (UNIQUE)
- nama_produk
- deskripsi
- harga_beli
- harga_jual
- gambar
- is_active
```

#### 3. **pelanggan**

Menyimpan data pelanggan

```
- id_pelanggan (PK)
- nama_pelanggan
- no_telp
- email
- alamat
- tipe_pelanggan (retail, grosir, member)
- is_active
```

#### 4. **penjualan**

Menyimpan header transaksi penjualan

```
- id_penjualan (PK)
- no_transaksi (UNIQUE)
- id_pelanggan (FK)
- id_user (FK)
- tgl_penjualan
- total_items
- subtotal
- diskon
- pajak
- total_harga
- status (pending, selesai, batal)
- metode_bayar
- jumlah_bayar
- kembalian
```

#### 5. **detail_penjualan**

Menyimpan detail item per transaksi

```
- id_detail (PK)
- id_penjualan (FK)
- id_produk (FK)
- qty
- harga_satuan
- diskon_item
- subtotal
```

#### 6. **stok_produk**

Menyimpan history stok produk

```
- id_stok (PK)
- id_produk (FK)
- stok_awal
- stok_masuk
- stok_keluar
- stok_akhir
- tgl_catat
- keterangan
```

---

## Fitur Utama

### 1. KASIR (Penjualan Real-Time)

**URL:** `http://localhost/pointofsale/sales`

**Fitur:**

- ✅ Input produk via klik atau search
- ✅ Keranjang belanja dinamis
- ✅ Hitung otomatis subtotal, diskon, pajak
- ✅ Multiple metode pembayaran
- ✅ Hitung kembalian otomatis
- ✅ Cetak struk otomatis
- ✅ Simpan transaksi ke database

**Workflow:**

1. Klik menu "Kasir" atau akses `/sales`
2. Cari produk atau klik produk di grid
3. Pilih pelanggan (opsional)
4. Atur diskon & pajak jika perlu
5. Input jumlah bayar
6. Klik CHECKOUT
7. Struk tercetak otomatis

### 2. DAFTAR TRANSAKSI

**URL:** `http://localhost/pointofsale/sales/daftar`

**Fitur:**

- ✅ Lihat semua transaksi yang pernah dibuat
- ✅ Status transaksi (pending, selesai, batal)
- ✅ Filter berdasarkan status
- ✅ Lihat detail transaksi
- ✅ Cetak ulang struk

### 3. LAPORAN PENJUALAN

#### a. Laporan Harian

**URL:** `http://localhost/pointofsale/laporan/harian`

- Filter by tanggal
- Tampilkan: transaksi, penjualan, diskon, pajak, pendapatan

#### b. Laporan Bulanan

**URL:** `http://localhost/pointofsale/laporan/bulanan`

- Filter by bulan & tahun
- Breakdown harian dalam 1 bulan
- Export ke CSV

#### c. Laporan Periode

**URL:** `http://localhost/pointofsale/laporan/periode`

- Custom range tanggal
- Comparison periode
- Export CSV

#### d. Laporan Stok

**URL:** `http://localhost/pointofsale/laporan/stok`

- Status stok semua produk
- Alert produk stok menipis (≤5)
- Nilai total stok
- Reorder button

#### e. Laporan Best Seller

**URL:** `http://localhost/pointofsale/laporan/best_seller`

- Top 20 produk paling laris
- Total qty & revenue per produk
- Custom period filter

#### f. Laporan Customer

**URL:** `http://localhost/pointofsale/laporan/customer`

- Penjualan per customer
- Total transaksi & total belanja
- Rata-rata belanja per customer

### 4. MANAJEMEN PRODUK

**Fitur:**

- Tambah/Edit/Hapus produk
- Manage kategori
- Set harga beli & jual
- Upload gambar produk

### 5. MANAJEMEN PELANGGAN

**Fitur:**

- Tambah/Edit/Hapus pelanggan
- Tipe pelanggan (retail, grosir, member)
- Contact information
- Riwayat pembelian

---

## Panduan Penggunaan

### Membuat Transaksi Penjualan

#### Method 1: Klik Produk

1. Buka halaman Kasir (`/sales`)
2. Klik produk di grid
3. Qty otomatis 1

#### Method 2: Search & Tambah

1. Type kode/nama produk di input
2. Klik "Tambah"
3. Sesuaikan qty di keranjang

#### Checkout

```
Subtotal: Rp 100.000
Diskon:   -Rp 5.000
Pajak:    +Rp 10.000
---------
Total:    Rp 105.000

Bayar: Rp 110.000
Kembalian: Rp 5.000
```

### Export Laporan

- Format: CSV (dapat dibuka di Excel)
- File tersimpan di `download` folder
- Columns: Tanggal, Transaksi, Penjualan, Diskon, Pajak, Pendapatan

---

## API Reference

### Sales Controller

#### GET `/sales`

**Deskripsi:** Halaman kasir utama
**Response:** HTML view kasir

#### POST `/sales/buat_transaksi`

**Deskripsi:** Buat transaksi baru
**Response:**

```json
{
	"success": true,
	"id_penjualan": 1,
	"no_transaksi": "TRX2026052500001"
}
```

#### GET `/sales/search_produk?q=keyword`

**Deskripsi:** Search produk
**Response:**

```json
[
	{
		"id_produk": 1,
		"nama_produk": "Lampu LED 10W",
		"kode_produk": "ELK001",
		"harga_jual": 50000
	}
]
```

#### GET `/sales/get_produk/:id`

**Deskripsi:** Get detail produk & stok
**Response:**

```json
{
    "success": true,
    "data": {...},
    "stok": 25
}
```

#### POST `/sales/tambah_item`

**Parameters:**

- `id_penjualan`: int
- `id_produk`: int
- `qty`: int

**Response:**

```json
{
    "success": true,
    "detail": [...]
}
```

#### POST `/sales/simpan_transaksi`

**Parameters:**

- `id_penjualan`: int
- `id_pelanggan`: int (optional)
- `metode_bayar`: string
- `diskon`: decimal
- `pajak`: decimal
- `jumlah_bayar`: decimal

**Response:**

```json
{
	"success": true,
	"id_penjualan": 1
}
```

### Laporan Controller

#### GET `/laporan/harian?date=2026-05-25`

**Deskripsi:** Laporan penjualan harian
**Response:** HTML view

#### GET `/laporan/bulanan?bulan=05&tahun=2026`

**Deskripsi:** Laporan penjualan bulanan
**Response:** HTML view

#### GET `/laporan/stok`

**Deskripsi:** Status stok semua produk
**Response:** HTML view dengan alerts

---

## Models

### Produk_model

```php
$this->load->model('Produk_model');

// GET
$produk = $this->Produk_model->get_all();
$produk = $this->Produk_model->get_by_id($id);
$produk = $this->Produk_model->search($keyword);

// INSERT
$this->Produk_model->insert($data);

// UPDATE
$this->Produk_model->update($id, $data);

// DELETE
$this->Produk_model->delete($id);
```

### Penjualan_model

```php
$this->load->model('Penjualan_model');

// Generate nomor transaksi
$no_transaksi = $this->Penjualan_model->generate_no_transaksi();

// INSERT penjualan
$id = $this->Penjualan_model->insert($data);

// INSERT detail
$this->Penjualan_model->insert_detail($id_penjualan, $id_produk, $qty, $harga);

// GET detail
$detail = $this->Penjualan_model->get_detail($id_penjualan);

// GET laporan
$laporan = $this->Penjualan_model->get_laporan($date_from, $date_to);
```

### Stok_model

```php
$this->load->model('Stok_model');

// Tambah stok
$this->Stok_model->tambah_stok($id_produk, $qty, $keterangan);

// Kurangi stok
$this->Stok_model->kurangi_stok($id_produk, $qty, $keterangan);

// GET stok habis
$stok_habis = $this->Stok_model->get_stok_habis();
```

---

## Troubleshooting

### Error: "No direct script access allowed"

**Solusi:** Pastikan URL dimulai dari index.php atau setup .htaccess

### Error: "Call to undefined function"

**Solusi:** Load helper di constructor:

```php
$this->load->helper('url');
$this->load->helper('form');
```

### Error: "The table 'tabel_name' doesn't exist"

**Solusi:**

1. Import ulang database `pos_system.sql`
2. Cek apakah database sudah terpilih

### Stok tidak berkurang setelah transaksi

**Solusi:**

1. Cek tabel `stok_produk` apakah sudah ada stok awal
2. Run:

```php
$this->load->model('Stok_model');
$this->Stok_model->insert_stok_awal($id_produk, 100);
```

### Laporan tidak menampilkan data

**Solusi:**

1. Cek tanggal transaksi di database
2. Filter range tanggal yang tepat
3. Cek status transaksi (harus "selesai")

---

## Tips & Best Practices

### 1. Input Stok Awal

Sebelum transaksi pertama, set stok awal produk:

```php
$this->load->model('Stok_model');
$this->Stok_model->insert_stok_awal(1, 50, date('Y-m-d'));
```

### 2. Backup Database Regular

```bash
mysqldump -u root -p database_name > backup_pos_$(date +%Y%m%d).sql
```

### 3. Audit Trail

Semua transaksi tercatat dengan:

- Nomor transaksi unik
- Timestamp
- User ID
- Status

### 4. Security

- Pastikan user terautentikasi sebelum akses kasir
- Validasi session di MY_Controller
- Use CSRF token untuk form

---

## Support & Maintenance

### Database Maintenance

```sql
-- Check database size
SELECT
    table_schema,
    ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
FROM information_schema.tables
GROUP BY table_schema;

-- Optimize tables
OPTIMIZE TABLE produk, pelanggan, penjualan, detail_penjualan, stok_produk;
```

### Performance Tips

1. Add indexes pada frequently queried columns
2. Archive old transactions yearly
3. Use pagination untuk list besar
4. Cache kategori produk

---

**Versi:** 1.0  
**Last Updated:** 25 May 2026  
**Author:** POS System Dev Team
