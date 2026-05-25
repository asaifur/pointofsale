# ✅ IMPLEMENTATION CHECKLIST - SISTEM POS

## Phase 1: Setup Foundation (30 menit)

### Database Setup

- [ ] Download/copy file `pos_system.sql`
- [ ] Buat database baru di MySQL (contoh: `toko_db`)
- [ ] Import file `pos_system.sql`
  ```bash
  mysql -u root -p toko_db < pos_system.sql
  ```
- [ ] Verify semua tabel sudah ada di phpMyAdmin
  - [ ] kategori_produk
  - [ ] produk
  - [ ] pelanggan
  - [ ] penjualan
  - [ ] detail_penjualan
  - [ ] stok_produk
  - [ ] pembayaran

### CodeIgniter Configuration

- [ ] Update `application/config/database.php`
  - [ ] Ubah `hostname` ke `localhost`
  - [ ] Ubah `username` ke `root`
  - [ ] Ubah `password` (kosong jika tidak ada)
  - [ ] Ubah `database` ke nama database yang dibuat
  - [ ] Ubah `dbdriver` ke `mysqli`
- [ ] Update `application/config/autoload.php`
  - [ ] Add `'database'` ke `$autoload['libraries']`
  - [ ] Add `'session'` ke `$autoload['libraries']`
  - [ ] Add `'pos'` ke `$autoload['helper']`

### File Verification

- [ ] Cek file models sudah ada di `application/models/`:
  - [ ] Produk_model.php
  - [ ] Pelanggan_model.php
  - [ ] Penjualan_model.php
  - [ ] Stok_model.php
- [ ] Cek file controllers sudah ada di `application/controllers/`:
  - [ ] Sales.php
  - [ ] Laporan.php
- [ ] Cek file views sudah ada:
  - [ ] `application/views/sales/kasir.php`
  - [ ] `application/views/sales/daftar.php`
  - [ ] `application/views/sales/detail.php`
  - [ ] `application/views/sales/struk.php`
  - [ ] `application/views/laporan/harian.php`
  - [ ] `application/views/laporan/bulanan.php`
  - [ ] `application/views/laporan/periode.php`
  - [ ] `application/views/laporan/stok.php`
  - [ ] `application/views/laporan/best_seller.php`
  - [ ] `application/views/laporan/customer.php`
- [ ] Cek file helper:
  - [ ] `application/helpers/pos_helper.php`
- [ ] Cek routes updated di `application/config/routes.php`

---

## Phase 2: Initial Data Setup (15 menit)

### Set Stok Awal Produk

**Via SQL (Recommended):**

```sql
-- Set stok awal untuk sample produk
INSERT INTO stok_produk (id_produk, stok_awal, stok_masuk, stok_keluar, stok_akhir, tgl_catat, keterangan)
VALUES
(1, 50, 0, 0, 50, DATE(NOW()), 'Stok awal - Lampu LED'),
(2, 100, 0, 0, 100, DATE(NOW()), 'Stok awal - Charger'),
(3, 75, 0, 0, 75, DATE(NOW()), 'Stok awal - Kaos Polos'),
(4, 200, 0, 0, 200, DATE(NOW()), 'Stok awal - Mie Instan');
```

- [ ] Verify stok sudah diset:
  ```sql
  SELECT * FROM stok_produk;
  ```

### Verify Sample Data

- [ ] Check kategori_produk (should have 4 rows)
- [ ] Check produk (should have 4 rows)
- [ ] Check pelanggan (should have 3 rows)

---

## Phase 3: Testing (20 menit)

### Access Testing

- [ ] Akses aplikasi: `http://localhost/pointofsale`
- [ ] Akses kasir: `http://localhost/pointofsale/sales`
- [ ] Akses daftar transaksi: `http://localhost/pointofsale/sales/daftar`
- [ ] Akses laporan harian: `http://localhost/pointofsale/laporan/harian`

### Kasir Functionality Testing

- [ ] Halaman kasir sudah load (cek produk di grid)
- [ ] Search produk berfungsi
- [ ] Klik produk → item masuk ke keranjang
- [ ] Hitung subtotal otomatis
- [ ] Input diskon & pajak berfungsi
- [ ] Hitung kembalian otomatis
- [ ] Pilih metode pembayaran
- [ ] Klik CHECKOUT
- [ ] Struk tercetak (print dialog muncul)
- [ ] Stok berkurang di database (check `stok_produk` tabel)

### Laporan Testing

- [ ] Laporan harian menampilkan data
- [ ] Filter tanggal berfungsi
- [ ] Laporan bulanan menampilkan breakdown
- [ ] Laporan stok menampilkan status
- [ ] Alert stok menipis muncul (jika qty ≤ 5)
- [ ] Export CSV berfungsi

### Database Testing

- [ ] Check `penjualan` table → should have 1 row
- [ ] Check `detail_penjualan` → should have items
- [ ] Check `stok_produk` → history tercatat
- [ ] Check stok_akhir sudah berkurang

---

## Phase 4: Customization (Optional)

### Branding

- [ ] Update nama toko di views (ganti "TOKO ABC")
- [ ] Add logo di halaman kasir
- [ ] Customize warna tema
- [ ] Update footer dengan info toko

### Business Rules

- [ ] Adjust default pajak rate
- [ ] Adjust alert stok threshold (default ≤5)
- [ ] Add komponen bisnis spesifik
- [ ] Customize report filters

### Template Integration

- [ ] Integrate dengan existing template yang ada
- [ ] Update navigation menu
- [ ] Add sidebar menu untuk sales & laporan

---

## Phase 5: Deployment Preparation

### Security

- [ ] Set CI_DEBUG = FALSE (untuk production)
- [ ] Setup CI_ENVIRONMENT = 'production'
- [ ] Configure error handling
- [ ] Setup audit logging
- [ ] Review database backups

### Performance

- [ ] Enable query caching (jika perlu)
- [ ] Setup database indexes (sudah ada)
- [ ] Test dengan 1000+ transaksi
- [ ] Monitor query performance

### Backup & Recovery

- [ ] Setup automated daily backup
  ```bash
  mysqldump -u root -p toko_db > backup_$(date +\%Y\%m\%d).sql
  ```
- [ ] Test restore dari backup
- [ ] Document backup procedure

---

## Phase 6: Training & Go-Live

### User Training

- [ ] Train kasir: Input transaksi
- [ ] Train kasir: Cetak struk
- [ ] Train manager: Akses laporan
- [ ] Train admin: Stok management

### Documentation

- [ ] Provide SETUP_GUIDE.md ke user
- [ ] Provide POS_DOCUMENTATION.md ke developer
- [ ] Create training materials (if needed)
- [ ] Setup support contact

### Go-Live

- [ ] Announce go-live date
- [ ] Migrate historical data (jika ada)
- [ ] Setup production database
- [ ] Monitor first day operation
- [ ] Be ready for quick fixes

---

## Ongoing Maintenance

### Daily

- [ ] Monitor system performance
- [ ] Check for errors in logs
- [ ] Verify transactions recorded

### Weekly

- [ ] Review laporan penjualan
- [ ] Check stok alerts
- [ ] Verify backup success

### Monthly

- [ ] Optimize database
- [ ] Review transaction patterns
- [ ] Update inventory audit

### Quarterly

- [ ] Full database maintenance
- [ ] Archive old transactions (if db too large)
- [ ] Review & optimize queries
- [ ] Performance tuning

---

## Troubleshooting Quick Reference

| Issue                  | Solution                          |
| ---------------------- | --------------------------------- |
| "Table doesn't exist"  | Import pos_system.sql ulang       |
| Stok tidak berkurang   | Set stok awal dengan INSERT query |
| Halaman blank          | Check logs di application/logs/   |
| Search tidak berfungsi | Check browser console untuk error |
| Kasir tidak responsive | Cek DB connection, cek network    |

---

## Contact & Support

- **Database Issue:** Check database.php config & MySQL running
- **Code Issue:** Check application/logs/ untuk error detail
- **Feature Request:** Update di models/controllers sesuai requirement
- **Performance Issue:** Optimize query, add index, cache data

---

## Sign-Off

- [ ] Setup completed & verified by: ********\_******** Date: **\_\_\_**
- [ ] Testing completed by: ********\_******** Date: **\_\_\_**
- [ ] User training completed by: ********\_******** Date: **\_\_\_**
- [ ] System approved for go-live by: ********\_******** Date: **\_\_\_**

---

## Additional Notes

```
_________________________________________________________________

_________________________________________________________________

_________________________________________________________________

_________________________________________________________________
```

---

**Document Version:** 1.0  
**Last Updated:** 25 May 2026  
**Status:** Ready for Implementation
