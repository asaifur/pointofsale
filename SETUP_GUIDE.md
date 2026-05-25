# QUICK START GUIDE - SISTEM POS

## 🚀 Setup Cepat (5 Menit)

### Langkah 1: Import Database

**Cara 1 - Command Line:**

```bash
cd c:\xampp\htdocs\pointofsale
mysql -u root -p nama_database < pos_system.sql
```

**Cara 2 - phpMyAdmin:**

1. Buka `http://localhost/phpmyadmin`
2. Login dengan user root
3. Buat database baru (contoh: `toko_db`)
4. Tab "Import" → Pilih file `pos_system.sql`
5. Klik "Import"

### Langkah 2: Konfigurasi Database

Edit file: `application/config/database.php`

Ubah bagian ini:

```php
$db['default'] = array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '',              // kosong jika tidak ada password
    'database' => 'toko_db',       // sesuaikan dengan nama database
    'dbdriver' => 'mysqli',
    // ... sisanya biarkan default
);
```

### Langkah 3: Aktifkan Helper POS

Edit file: `application/config/autoload.php`

Cari baris `$autoload['helper']` dan ubah menjadi:

```php
$autoload['helper'] = array('url', 'form', 'pos');
```

### Langkah 4: Test Akses

Buka browser dan akses:

- **Kasir:** `http://localhost/pointofsale/sales`
- **Laporan:** `http://localhost/pointofsale/laporan`

---

## 📝 Set Stok Awal Produk

**Penting!** Sebelum kasir mulai bekerja, atur stok awal produk.

### Via Code (Recommended)

Buat file baru: `application/controllers/Setup.php`

```php
<?php
class Setup extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Stok_model');
    }

    public function init_stok()
    {
        // Set stok awal untuk setiap produk
        $this->Stok_model->insert_stok_awal(1, 50);  // Produk 1: 50 unit
        $this->Stok_model->insert_stok_awal(2, 100); // Produk 2: 100 unit
        $this->Stok_model->insert_stok_awal(3, 75);  // Produk 3: 75 unit
        $this->Stok_model->insert_stok_awal(4, 200); // Produk 4: 200 unit

        echo "Stok awal berhasil diset!";
    }
}
?>
```

Akses: `http://localhost/pointofsale/setup/init_stok`

### Via SQL

```sql
INSERT INTO stok_produk (id_produk, stok_awal, stok_masuk, stok_keluar, stok_akhir, tgl_catat, keterangan)
VALUES
(1, 50, 0, 0, 50, '2026-05-25', 'Stok awal'),
(2, 100, 0, 0, 100, '2026-05-25', 'Stok awal'),
(3, 75, 0, 0, 75, '2026-05-25', 'Stok awal'),
(4, 200, 0, 0, 200, '2026-05-25', 'Stok awal');
```

---

## 🏪 Membuat Transaksi Pertama

### Step by Step

1. **Buka Kasir**
   - Akses: `http://localhost/pointofsale/sales`

2. **Pilih Produk**
   - Klik produk di grid, atau
   - Ketik kode produk di search

3. **Atur Qty**
   - Qty otomatis = 1
   - Sesuaikan di keranjang jika perlu

4. **Tambah Produk Lain (Opsional)**
   - Ulangi langkah 2-3

5. **Pilih Pelanggan (Opsional)**
   - Pilih customer atau kosongkan untuk penjualan umum

6. **Set Diskon & Pajak (Opsional)**
   - Diskon dalam Rp
   - Pajak dalam %

7. **Input Pembayaran**
   - Pilih metode pembayaran
   - Masukkan jumlah bayar

8. **CHECKOUT**
   - Klik tombol CHECKOUT
   - Struk otomatis tercetak

---

## 📊 Melihat Laporan

### Laporan Harian

- URL: `/laporan/harian?date=2026-05-25`
- Lihat: Total transaksi, penjualan, diskon, pajak hari ini
- Export: Klik tombol Export CSV

### Laporan Best Seller

- URL: `/laporan/best_seller`
- Lihat: 20 produk paling laris
- Filter: Custom period

### Laporan Stok

- URL: `/laporan/stok`
- Alert: Produk stok menipis (≤5)
- Nilai: Total nilai stok keseluruhan

### Laporan Customer

- URL: `/laporan/customer`
- Lihat: Penjualan per customer
- Insight: Rata-rata belanja per customer

---

## 🔧 Common Tasks

### Menambah Produk Baru

Via Controller (Create `Produk.php`):

```php
<?php
class Produk extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Produk_model');
    }

    public function tambah()
    {
        $data = [
            'id_kategori' => 1,
            'kode_produk' => 'ELK003',
            'nama_produk' => 'Produk Baru',
            'harga_beli' => 100000,
            'harga_jual' => 150000,
            'deskripsi' => 'Deskripsi produk'
        ];

        $this->Produk_model->insert($data);
        echo "Produk berhasil ditambahkan";
    }
}
?>
```

### Menambah Pelanggan Baru

Via SQL:

```sql
INSERT INTO pelanggan (nama_pelanggan, no_telp, email, alamat, tipe_pelanggan)
VALUES ('Toko XYZ', '082123456789', 'toko@xyz.com', 'Jl. Raya No. 1', 'grosir');
```

### Cek Stok Produk

Via Database Query:

```sql
SELECT p.nama_produk, s.stok_akhir
FROM stok_produk s
JOIN produk p ON s.id_produk = p.id_produk
WHERE s.id_stok IN (
    SELECT MAX(id_stok) FROM stok_produk GROUP BY id_produk
);
```

### Backup Database

```bash
mysqldump -u root -p nama_database > backup_$(date +%Y%m%d_%H%M%S).sql
```

---

## ⚠️ Troubleshooting Umum

### ❌ "Table doesn't exist"

**Solusi:**

1. Pastikan sudah import `pos_system.sql`
2. Pastikan database sudah dipilih
3. Run di phpMyAdmin: `pos_system.sql`

### ❌ "Stok tidak berkurang"

**Solusi:**

1. Cek apakah stok awal sudah diset
2. Run: `INSERT INTO stok_produk ... (lihat di atas)`
3. Cek status transaksi harus "selesai"

### ❌ "Halaman blank / error"

**Solusi:**

1. Cek error log: `application/logs/`
2. Pastikan database sudah terkoneksi
3. Cek database.php sudah dikonfigurasi

### ❌ "Search produk tidak berfungsi"

**Solusi:**

1. Pastikan helper sudah di-load
2. Pastikan helper POS sudah di autoload.php
3. Run browser console: F12 → Console, cek error

### ❌ Tombol CHECKOUT tidak merespons

**Solusi:**

1. Pastikan ada item di keranjang
2. Cek jumlah bayar sudah diisi
3. Buka console (F12) lihat error detail

---

## 📚 File Penting

| File                                     | Fungsi             |
| ---------------------------------------- | ------------------ |
| `pos_system.sql`                         | Database schema    |
| `application/controllers/Sales.php`      | Controller kasir   |
| `application/controllers/Laporan.php`    | Controller laporan |
| `application/models/Penjualan_model.php` | Model transaksi    |
| `application/models/Stok_model.php`      | Model stok         |
| `application/views/sales/kasir.php`      | View kasir         |
| `application/config/routes.php`          | URL routing        |

---

## 🎓 Next Steps

1. ✅ Setup database
2. ✅ Configure database.php
3. ✅ Set stok awal
4. ✅ Buat transaksi pertama
5. ⚪ Customization (add logo, brand, etc.)
6. ⚪ Integrasi payment gateway
7. ⚪ Backup & maintenance schedule

---

## 📞 Support

Untuk pertanyaan atau issue, buka file `POS_DOCUMENTATION.md` untuk detail lengkap.

---

**Selamat menggunakan sistem POS!** 🎉

Generated: 25 May 2026
