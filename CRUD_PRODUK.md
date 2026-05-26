# 📦 CRUD PRODUK - Dokumentasi

## 📋 Overview

Sistem manajemen produk lengkap dengan fitur Create, Read, Update, Delete (CRUD) yang terintegrasi dengan sistem POS.

## ✨ Fitur Utama

### 1. **READ - Daftar Produk**

- Menampilkan semua produk aktif
- Search produk berdasarkan nama, kode, atau kategori
- Filter berdasarkan kategori
- Tampilan table dengan pagination
- Margin calculation display
- Quick action buttons (View, Edit, Delete)

### 2. **CREATE - Tambah Produk Baru**

- Form input yang user-friendly
- Validasi input lengkap
- Automatic margin calculation
- Support untuk kategori
- Unique kode produk validation
- Preview margin dalam real-time

### 3. **UPDATE - Edit Produk**

- Edit detail produk
- Automatic margin recalculation
- Prevent kode produk change (read-only)
- Validasi input yang ketat
- Audit trail (updated_at)

### 4. **DELETE - Hapus Produk**

- Soft delete (data tidak benar-benar terhapus)
- Confirmation dialog
- Prevents accidental deletion

### 5. **DETAIL - Lihat Detail Produk**

- Informasi lengkap produk
- Price analysis
- Margin calculation detail
- Created/Updated timestamps

---

## 📁 File Structure

```
application/
├── controllers/
│   └── Produk.php                  ← Controller CRUD
├── models/
│   └── Produk_model.php            ← Model dengan query
├── views/
│   └── produk/
│       ├── index.php               ← Daftar produk
│       ├── form.php                ← Form tambah/edit
│       └── detail.php              ← Detail produk
└── config/
    └── routes.php                  ← Routes untuk produk
```

---

## 🛣️ Routes

```php
// List produk
GET  /produk                 → produk/index

// Tambah produk
GET  /produk/tambah          → produk/tambah (form)
POST /produk/simpan          → produk/simpan (save)

// Edit produk
GET  /produk/edit/1          → produk/edit/$1 (form)
POST /produk/update/1        → produk/update/$1 (save)

// Detail produk
GET  /produk/detail/1        → produk/detail/$1

// Hapus produk
GET  /produk/hapus/1         → produk/hapus/$1

// API
GET  /produk/get_detail/1    → produk/get_detail/$1 (JSON)
GET  /produk/check_kode/ABC  → produk/check_kode/$1 (JSON)
```

---

## 🎯 Controller Methods

### `index()` - Daftar Produk

```php
public function index()
{
    // Parameters:
    // - search: filter berdasarkan search
    // - kategori: filter berdasarkan kategori
}
```

### `tambah()` - Form Tambah

```php
public function tambah()
{
    // Tampilkan form tambah produk baru
}
```

### `simpan()` - Save Produk Baru

```php
public function simpan()
{
    // Validasi input
    // Insert produk baru
    // Redirect ke daftar produk
}
```

### `edit($id_produk)` - Form Edit

```php
public function edit($id_produk)
{
    // Load data produk
    // Tampilkan form edit
}
```

### `update($id_produk)` - Save Perubahan

```php
public function update($id_produk)
{
    // Validasi input
    // Update data produk
    // Redirect ke daftar produk
}
```

### `hapus($id_produk)` - Delete Produk

```php
public function hapus($id_produk)
{
    // Soft delete (set is_active = 0)
    // Redirect ke daftar produk
}
```

### `detail($id_produk)` - Detail Produk

```php
public function detail($id_produk)
{
    // Load data produk lengkap
    // Tampilkan halaman detail
}
```

---

## 📊 Database Fields

```sql
CREATE TABLE produk (
    id_produk INT PRIMARY KEY AUTO_INCREMENT,
    id_kategori INT NOT NULL,
    kode_produk VARCHAR(50) UNIQUE NOT NULL,
    nama_produk VARCHAR(150) NOT NULL,
    deskripsi TEXT,
    harga_beli DECIMAL(12, 2) NOT NULL,
    harga_jual DECIMAL(12, 2) NOT NULL,
    gambar VARCHAR(255),
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## ✅ Validasi Rules

### Tambah Produk

- `id_kategori`: Required, numeric
- `kode_produk`: Required, unique, tidak boleh ada duplikat
- `nama_produk`: Required, min 3 karakter
- `harga_beli`: Required, numeric, >= 0
- `harga_jual`: Required, numeric, >= 0

### Edit Produk

- `id_kategori`: Required, numeric
- `nama_produk`: Required, min 3 karakter
- `harga_beli`: Required, numeric, >= 0
- `harga_jual`: Required, numeric, >= 0
- `kode_produk`: Read-only (tidak bisa diubah)

---

## 💰 Margin Calculation

### Formula

```
Margin (Rp) = Harga Jual - Harga Beli
Margin (%) = (Margin Rp / Harga Beli) × 100
```

### Real-time Calculation

- Automatic saat input harga beli/jual
- Display di form dan detail
- Color indicator (Hijau: profit, Merah: rugi, Abu: break-even)

---

## 🎨 UI Features

### List View

- Search bar dengan placeholder
- Category filter dropdown
- Reset button
- Table dengan:
  - Nomor urut
  - Kode produk (badge)
  - Nama produk dengan deskripsi
  - Kategori
  - Harga beli (right-aligned)
  - Harga jual (right-aligned)
  - Margin % (badge)
  - Action buttons (View, Edit, Delete)
- Pagination & sorting
- Record count badge

### Form View

- Organized fields:
  - Kategori (dropdown)
  - Kode produk
  - Nama produk
  - Deskripsi (textarea)
  - Harga beli
  - Harga jual
- Real-time margin display
- Margin analysis card
- Status indicator
- Responsive design
- Back & Cancel buttons

### Detail View

- Product header dengan kode
- Kategori & Status badge
- Deskripsi
- Price information (Harga Beli/Jual)
- Margin analysis box
- Timestamps (Created/Updated)
- Action buttons (Edit/Delete)
- Sidebar dengan price analysis table

---

## 🔐 Security

### Access Control

- Login required (session check)
- Redirect ke auth jika belum login

### Data Validation

- Server-side validation di controller
- Unique constraint untuk kode_produk
- Numeric validation untuk harga

### SQL Injection Prevention

- Use CodeIgniter query builder
- Parameterized queries
- Input sanitization

### Soft Delete

- Data tidak dihapus, hanya set is_active = 0
- Keeps audit trail
- Dapat di-restore jika perlu

---

## 📱 Responsive Design

- Mobile-friendly form
- Responsive table dengan scroll
- Bootstrap grid system (col-md-6, col-md-12)
- Touch-friendly buttons
- Flexible layout

---

## 🧪 Testing Checklist

- [ ] Create produk dengan validasi benar
- [ ] Create produk reject jika ada duplikat kode
- [ ] Read daftar produk tampil benar
- [ ] Read search produk bekerja
- [ ] Read kategori filter bekerja
- [ ] Update produk dengan kode read-only
- [ ] Margin calculation real-time
- [ ] Delete produk dengan confirmation
- [ ] Detail produk tampil lengkap
- [ ] Soft delete (is_active = 0)

---

## 🚀 Usage

### Akses Daftar Produk

```
http://localhost/pointofsale/produk
```

### Tambah Produk

```
1. Klik tombol "Tambah Produk"
2. Isi form (pilih kategori, isi kode, nama, harga)
3. Lihat margin calculation real-time
4. Klik "Tambah Produk"
```

### Edit Produk

```
1. Klik icon "Pencil" di row produk
2. Ubah data (kode tidak bisa diubah)
3. Margin auto-calculate
4. Klik "Simpan Perubahan"
```

### Lihat Detail

```
1. Klik icon "Eye" di row produk
2. Lihat semua detail dan analisis harga
3. Klik "Edit" atau "Hapus" dari detail page
```

### Hapus Produk

```
1. Klik icon "Trash" di row produk
2. Confirm di modal
3. Produk akan di-soft delete
```

---

## 📈 Next Steps (Enhancements)

1. **Gambar Produk**: Upload & display product images
2. **Stok Integration**: Display current stok dari stok_produk table
3. **SKU Generator**: Auto-generate SKU unique
4. **Bulk Upload**: CSV import produk
5. **Product Variants**: Support ukuran/warna
6. **Price History**: Track harga changes
7. **Supplier Info**: Link dengan supplier
8. **Product Reviews**: Customer ratings
9. **Export**: Export produk ke CSV/Excel
10. **Barcode**: Generate & print barcode

---

## ⚡ Performance Tips

1. Add indexes pada frequently searched fields
2. Limit produk per page (pagination)
3. Cache kategori list
4. Optimize images jika ada
5. Use CDN untuk static assets

---

## 🐛 Troubleshooting

### Produk tidak tampil

- Check is_active = 1
- Check database connection
- Check route configuration

### Validasi error

- Check form_validation rules
- Check POST data sent correctly
- Check error messages displayed

### Margin tidak calculate

- Check JavaScript enabled
- Check harga input format (numeric)
- Check formula calculation

---

## 📞 Support

Untuk bantuan lebih lanjut, silakan:

1. Cek dokumentasi lengkap di POS_DOCUMENTATION.md
2. Review controller code di controllers/Produk.php
3. Review model code di models/Produk_model.php

---

**Last Updated**: 2026-05-26
**Version**: 1.0.0
**Status**: Production Ready ✅
