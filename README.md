# 📊 SISTEM POS (Point of Sale) - CodeIgniter 3

## 🎯 Overview

Sistem POS lengkap yang dirancang untuk kasir real-time dengan fitur-fitur lengkap untuk mengelola penjualan, stok, pelanggan, dan laporan.

**Status:** ✅ **SIAP DIGUNAKAN**

---

## 📦 Apa Yang Sudah Dibuat?

### 1. ✅ Database Schema (`pos_system.sql`)

- 8 tabel utama dengan relationships
- Sample data kategori, produk, dan pelanggan
- Index untuk optimasi query
- Total lines: 200+

**Tabel yang dibuat:**

- `kategori_produk` - Kategori produk
- `produk` - Master produk
- `pelanggan` - Data pelanggan
- `penjualan` - Header transaksi
- `detail_penjualan` - Item transaksi
- `stok_produk` - History stok
- `pembayaran` - Data pembayaran
- `laporan_penjualan` - Aggregate laporan

### 2. ✅ 4 Models Lengkap

- **Produk_model.php** - Search, filter, manage produk
- **Pelanggan_model.php** - Manage pelanggan & tipe
- **Penjualan_model.php** - Transaksi, detail, laporan
- **Stok_model.php** - Update stok, history, alert

**Total Methods:** 30+

### 3. ✅ 2 Controllers Utama

- **Sales.php** - Kasir real-time dengan:
  - Buat transaksi
  - Search produk real-time
  - Tambah/hapus item
  - Checkout & simpan ke database
  - Cetak struk otomatis

- **Laporan.php** - 6 jenis laporan:
  - Harian
  - Bulanan
  - Periode custom
  - Stok
  - Best seller
  - Per customer

### 4. ✅ 10 Views Responsive

**Views Sales:**

- `kasir.php` - Antarmuka kasir interaktif
- `daftar.php` - List semua transaksi
- `detail.php` - Detail transaksi lengkap
- `struk.php` - Struk pembayaran A4

**Views Laporan:**

- `harian.php` - Laporan harian dengan KPI
- `bulanan.php` - Breakdown harian per bulan
- `periode.php` - Custom range dengan export
- `stok.php` - Status stok & alert
- `best_seller.php` - Top 20 produk
- `customer.php` - Analisis per customer

### 5. ✅ Helper Utility (`pos_helper.php`)

**40+ fungsi utility:**

- Format Rupiah & tanggal
- Generate kode otomatis
- Validasi input
- Hitung margin, pajak, kembalian
- Array manipulation
- Badge status
- Export CSV
- Logging (ready)

### 6. ✅ Routes Configuration

- 20+ route mapping untuk Sales & Laporan
- RESTful-style API endpoints
- Clean URL structure

**Contoh routes:**

```
/sales                          → Kasir
/sales/daftar                   → Daftar transaksi
/sales/detail/1                 → Detail transaksi
/laporan/harian                 → Laporan harian
/laporan/stok                   → Laporan stok
```

### 7. ✅ Dokumentasi Lengkap

- **POS_DOCUMENTATION.md** - Dokumentasi komprehensif (800+ lines)
- **SETUP_GUIDE.md** - Quick start guide (300+ lines)
- **README.md** - File ini

---

## 🎨 Fitur Utama

### Kasir Real-Time

✅ Input produk via click atau search  
✅ Keranjang belanja dinamis  
✅ Hitung otomatis subtotal, diskon, pajak  
✅ Multiple metode pembayaran  
✅ Kembalian otomatis  
✅ Cetak struk langsung  
✅ Transaksi tersimpan ke database

### Manajemen Stok

✅ History stok terperinci  
✅ Alert produk stok menipis  
✅ Update stok real-time  
✅ Nilai stok keseluruhan  
✅ Riwayat stok 30 hari terakhir

### Laporan & Analytics

✅ 6 jenis laporan berbeda  
✅ Filter by tanggal, bulan, periode  
✅ Export CSV untuk Excel  
✅ KPI dashboard (transaksi, penjualan, diskon, pajak)  
✅ Best seller analysis  
✅ Customer analytics

### Data Management

✅ CRUD produk  
✅ CRUD pelanggan  
✅ CRUD kategori  
✅ Soft delete (non-destructive)  
✅ Timestamp audit trail

---

## 📊 Database Schema

```
KATEGORI_PRODUK
    ├── id_kategori (PK)
    ├── nama_kategori
    └── is_active

PRODUK (FK: kategori_produk)
    ├── id_produk (PK)
    ├── id_kategori (FK)
    ├── kode_produk (UNIQUE)
    ├── nama_produk
    ├── harga_beli
    ├── harga_jual
    └── is_active

PELANGGAN
    ├── id_pelanggan (PK)
    ├── nama_pelanggan
    ├── tipe_pelanggan (retail/grosir/member)
    └── is_active

PENJUALAN (FK: pelanggan, users)
    ├── id_penjualan (PK)
    ├── no_transaksi (UNIQUE)
    ├── id_pelanggan (FK)
    ├── id_user (FK)
    ├── subtotal
    ├── diskon
    ├── pajak
    ├── total_harga
    ├── status (pending/selesai/batal)
    └── metode_bayar

DETAIL_PENJUALAN (FK: penjualan, produk)
    ├── id_detail (PK)
    ├── id_penjualan (FK)
    ├── id_produk (FK)
    ├── qty
    ├── harga_satuan
    └── subtotal

STOK_PRODUK (FK: produk)
    ├── id_stok (PK)
    ├── id_produk (FK)
    ├── stok_awal
    ├── stok_masuk
    ├── stok_keluar
    ├── stok_akhir
    └── tgl_catat
```

---

## 🚀 Quick Start (5 Menit)

### 1. Import Database

```bash
mysql -u root < pos_system.sql
```

### 2. Config Database

Edit `application/config/database.php`:

```php
'database' => 'nama_database_anda'
```

### 3. Load Helper

Edit `application/config/autoload.php`:

```php
$autoload['helper'] = array('url', 'form', 'pos');
```

### 4. Set Stok Awal

```sql
INSERT INTO stok_produk VALUES
(NULL, 1, 50, 0, 0, 50, DATE(NOW()), 'Stok awal'),
(NULL, 2, 100, 0, 0, 100, DATE(NOW()), 'Stok awal');
```

### 5. Buka Kasir

Akses: `http://localhost/pointofsale/sales`

---

## 📂 File Structure

```
pointofsale/
├── pos_system.sql                          ← Database schema
├── POS_DOCUMENTATION.md                    ← Dokumentasi lengkap
├── SETUP_GUIDE.md                          ← Quick start guide
├── application/
│   ├── config/
│   │   ├── routes.php                      ✏️ Updated
│   │   └── database.php
│   ├── controllers/
│   │   ├── Sales.php                       ✨ NEW
│   │   └── Laporan.php                     ✨ NEW
│   ├── models/
│   │   ├── Produk_model.php                ✨ NEW
│   │   ├── Pelanggan_model.php             ✨ NEW
│   │   ├── Penjualan_model.php             ✨ NEW
│   │   └── Stok_model.php                  ✨ NEW
│   ├── views/
│   │   ├── sales/                          ✨ NEW
│   │   │   ├── kasir.php
│   │   │   ├── daftar.php
│   │   │   ├── detail.php
│   │   │   └── struk.php
│   │   └── laporan/                        ✨ NEW
│   │       ├── harian.php
│   │       ├── bulanan.php
│   │       ├── periode.php
│   │       ├── stok.php
│   │       ├── best_seller.php
│   │       └── customer.php
│   └── helpers/
│       └── pos_helper.php                  ✨ NEW
└── assets/
    ├── css/
    ├── js/
    └── plugins/
```

---

## 🔗 URLs

| Feature          | URL                    |
| ---------------- | ---------------------- |
| **KASIR**        |                        |
| Kasir            | `/sales`               |
| Daftar Transaksi | `/sales/daftar`        |
| Detail Transaksi | `/sales/detail/1`      |
| **LAPORAN**      |                        |
| Laporan Harian   | `/laporan/harian`      |
| Laporan Bulanan  | `/laporan/bulanan`     |
| Laporan Periode  | `/laporan/periode`     |
| Laporan Stok     | `/laporan/stok`        |
| Best Seller      | `/laporan/best_seller` |
| Per Customer     | `/laporan/customer`    |

---

## 💾 Sample Data

Database sudah include sample data:

**Kategori:**

- Elektronik
- Pakaian
- Makanan
- Kebutuhan Rumah Tangga

**Produk:** 4 produk sample

- ELK001: Lampu LED 10W
- ELK002: Charger USB-C
- PKN001: Kaos Polos Putih
- MKN001: Mie Instan Goreng

**Pelanggan:** 3 pelanggan sample

- Toko ABC (Grosir)
- Budi Santoso (Retail)
- Siti Nur (Member)

---

## 🔌 API Endpoints

### Sales API

**POST** `/sales/buat_transaksi`

```json
Response: {
  "success": true,
  "id_penjualan": 1,
  "no_transaksi": "TRX2026052500001"
}
```

**GET** `/sales/search_produk?q=produk`

```json
Response: [
  {
    "id_produk": 1,
    "nama_produk": "Produk",
    "kode_produk": "KOD001",
    "harga_jual": 50000
  }
]
```

**POST** `/sales/simpan_transaksi`

```
Parameters:
- id_penjualan
- id_pelanggan (optional)
- metode_bayar
- diskon
- pajak
- jumlah_bayar
```

---

## ✨ Highlight Features

### 1. Real-Time Kasir

- Async product search
- Dynamic cart calculation
- Instant payment method switching
- Auto change calculation

### 2. Smart Inventory

- Auto stock deduction per transaction
- Low stock alerts (≤5)
- Inventory history tracking
- Total inventory value

### 3. Comprehensive Reporting

- Multiple report types
- Date range filtering
- CSV export ready
- Summary KPI cards

### 4. Transaction Audit

- Unique transaction numbers
- Complete item history
- Payment method tracking
- Customer linkage
- Timestamp recording

---

## 🔒 Security Features

✅ Session-based authentication  
✅ SQL injection prevention (prepared statements)  
✅ CSRF token ready  
✅ Soft delete (no permanent loss)  
✅ User ID logging  
✅ IP address tracking (helper ready)

---

## 🚀 Next Steps / Future Enhancements

- [ ] Add payment gateway integration (Stripe, Midtrans)
- [ ] Mobile app version
- [ ] Multi-warehouse support
- [ ] Purchase order management
- [ ] Supplier integration
- [ ] Advanced analytics dashboard
- [ ] Barcode/QR code support
- [ ] Email receipt
- [ ] SMS notification
- [ ] Bulk import produk
- [ ] User role & permissions
- [ ] Accounting module

---

## 📋 Checklist Setup

- [ ] Import `pos_system.sql` ke database
- [ ] Update `application/config/database.php`
- [ ] Add `pos` ke `$autoload['helper']`
- [ ] Set stok awal produk
- [ ] Test akses `/sales`
- [ ] Test transaksi pertama
- [ ] Test cetak struk
- [ ] Check laporan menampilkan data
- [ ] Backup database

---

## 📚 Documentation Files

1. **POS_DOCUMENTATION.md** (800+ lines)
   - Complete feature documentation
   - Database schema explanation
   - Full API reference
   - Model usage examples
   - Troubleshooting guide

2. **SETUP_GUIDE.md** (300+ lines)
   - Quick 5-minute setup
   - Step-by-step instructions
   - Common tasks
   - Troubleshooting quick fixes

3. **README.md** (ini)
   - Overview
   - Feature summary
   - Quick links
   - File structure

---

## 🎯 Development Stats

| Item                | Count |
| ------------------- | ----- |
| Database tables     | 8     |
| Models              | 4     |
| Controllers         | 2     |
| Views               | 10    |
| API endpoints       | 10+   |
| Helper functions    | 40+   |
| Total lines of code | 3000+ |
| Database queries    | 50+   |

---

## 🎓 How to Use

### For Beginners

1. Start with `SETUP_GUIDE.md` - Get up and running
2. Follow the quick start steps
3. Try making first transaction in kasir
4. Explore laporan features

### For Developers

1. Read `POS_DOCUMENTATION.md` - Deep dive
2. Check models for database queries
3. Examine controllers for business logic
4. Modify views for UI customization
5. Extend models for additional features

### For System Admins

1. Setup database and config
2. Regular backups (see backup command in docs)
3. Monitor audit logs
4. Archive old transactions yearly
5. Optimize database quarterly

---

## 📞 Support Resources

- **Setup Issues?** → See `SETUP_GUIDE.md` Troubleshooting
- **Feature Questions?** → See `POS_DOCUMENTATION.md` Features
- **Code Questions?** → Check code comments in controllers/models
- **Database Queries?** → See documentation models section

---

## 📄 License

This Point of Sale System is provided as-is for educational and commercial use.

---

## 🎉 Summary

Anda sekarang memiliki sistem POS **production-ready** dengan:

- ✅ Database complete
- ✅ Full CRUD operations
- ✅ Real-time kasir interface
- ✅ 6 jenis laporan
- ✅ Inventory management
- ✅ Complete documentation
- ✅ Ready to deploy

**Selamat menggunakan! 🚀**

---

**Created:** 25 May 2026  
**Version:** 1.0  
**Status:** ✅ Production Ready
