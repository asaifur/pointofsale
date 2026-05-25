-- ======================================
-- DATABASE SCHEMA UNTUK SISTEM POS
-- ======================================

-- 1. TABEL KATEGORI PRODUK
CREATE TABLE IF NOT EXISTS kategori_produk (
    id_kategori INT PRIMARY KEY AUTO_INCREMENT,
    nama_kategori VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. TABEL PRODUK
CREATE TABLE IF NOT EXISTS produk (
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_kategori) REFERENCES kategori_produk(id_kategori) ON DELETE CASCADE
);

-- 3. TABEL STOK PRODUK
CREATE TABLE IF NOT EXISTS stok_produk (
    id_stok INT PRIMARY KEY AUTO_INCREMENT,
    id_produk INT NOT NULL,
    stok_awal INT DEFAULT 0,
    stok_masuk INT DEFAULT 0,
    stok_keluar INT DEFAULT 0,
    stok_akhir INT DEFAULT 0,
    tgl_catat DATE,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE CASCADE
);

-- 4. TABEL PELANGGAN
CREATE TABLE IF NOT EXISTS pelanggan (
    id_pelanggan INT PRIMARY KEY AUTO_INCREMENT,
    nama_pelanggan VARCHAR(100) NOT NULL,
    no_telp VARCHAR(15),
    email VARCHAR(100),
    alamat TEXT,
    kota VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    tipe_pelanggan ENUM('retail', 'grosir', 'member') DEFAULT 'retail',
    is_active TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 5. TABEL TRANSAKSI PENJUALAN
CREATE TABLE IF NOT EXISTS penjualan (
    id_penjualan INT PRIMARY KEY AUTO_INCREMENT,
    no_transaksi VARCHAR(50) UNIQUE NOT NULL,
    id_pelanggan INT,
    id_user INT NOT NULL,
    tgl_penjualan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_items INT DEFAULT 0,
    subtotal DECIMAL(12, 2) DEFAULT 0,
    diskon DECIMAL(12, 2) DEFAULT 0,
    pajak DECIMAL(12, 2) DEFAULT 0,
    total_harga DECIMAL(12, 2) DEFAULT 0,
    status ENUM('pending', 'selesai', 'batal') DEFAULT 'pending',
    metode_bayar ENUM('tunai', 'kartu_kredit', 'transfer', 'cek', 'lainnya') DEFAULT 'tunai',
    jumlah_bayar DECIMAL(12, 2) DEFAULT 0,
    kembalian DECIMAL(12, 2) DEFAULT 0,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON DELETE SET NULL,
    KEY idx_no_transaksi (no_transaksi),
    KEY idx_tgl_penjualan (tgl_penjualan),
    KEY idx_status (status)
);

-- 6. TABEL DETAIL PENJUALAN
CREATE TABLE IF NOT EXISTS detail_penjualan (
    id_detail INT PRIMARY KEY AUTO_INCREMENT,
    id_penjualan INT NOT NULL,
    id_produk INT NOT NULL,
    qty INT NOT NULL,
    harga_satuan DECIMAL(12, 2) NOT NULL,
    diskon_item DECIMAL(12, 2) DEFAULT 0,
    subtotal DECIMAL(12, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan) ON DELETE CASCADE,
    FOREIGN KEY (id_produk) REFERENCES produk(id_produk) ON DELETE RESTRICT
);

-- 7. TABEL PEMBAYARAN
CREATE TABLE IF NOT EXISTS pembayaran (
    id_pembayaran INT PRIMARY KEY AUTO_INCREMENT,
    id_penjualan INT NOT NULL,
    metode_bayar VARCHAR(50) NOT NULL,
    jumlah_bayar DECIMAL(12, 2) NOT NULL,
    status ENUM('pending', 'berhasil', 'gagal') DEFAULT 'pending',
    referensi VARCHAR(100),
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penjualan) REFERENCES penjualan(id_penjualan) ON DELETE CASCADE
);

-- 8. TABEL LAPORAN PENJUALAN (untuk reporting cepat)
CREATE TABLE IF NOT EXISTS laporan_penjualan (
    id_laporan INT PRIMARY KEY AUTO_INCREMENT,
    tgl_laporan DATE NOT NULL,
    total_transaksi INT DEFAULT 0,
    total_penjualan DECIMAL(12, 2) DEFAULT 0,
    total_diskon DECIMAL(12, 2) DEFAULT 0,
    total_pajak DECIMAL(12, 2) DEFAULT 0,
    total_pendapatan DECIMAL(12, 2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_date (tgl_laporan)
);

-- ======================================
-- INDEX UNTUK OPTIMASI QUERY
-- ======================================
CREATE INDEX idx_produk_kategori ON produk(id_kategori);
CREATE INDEX idx_produk_kode ON produk(kode_produk);
CREATE INDEX idx_stok_produk ON stok_produk(id_produk);
CREATE INDEX idx_penjualan_pelanggan ON penjualan(id_pelanggan);
CREATE INDEX idx_penjualan_user ON penjualan(id_user);
CREATE INDEX idx_detail_penjualan ON detail_penjualan(id_penjualan);

-- ======================================
-- SAMPLE DATA (OPSIONAL)
-- ======================================

-- Kategori Produk
INSERT INTO kategori_produk (nama_kategori, deskripsi) VALUES 
('Elektronik', 'Produk elektronik umum'),
('Pakaian', 'Pakaian dan aksesori'),
('Makanan', 'Makanan dan minuman'),
('Kebutuhan Rumah Tangga', 'Perlengkapan rumah tangga');

-- Produk Sample
INSERT INTO produk (id_kategori, kode_produk, nama_produk, harga_beli, harga_jual, deskripsi) VALUES 
(1, 'ELK001', 'Lampu LED 10W', 35000, 50000, 'Lampu LED hemat energi'),
(1, 'ELK002', 'Charger USB-C', 40000, 65000, 'Charger cepat USB-C'),
(2, 'PKN001', 'Kaos Polos Putih', 25000, 45000, 'Kaos polos 100% cotton'),
(3, 'MKN001', 'Mie Instan Goreng', 2500, 5000, 'Mie instan rasa goreng');

-- Pelanggan Sample
INSERT INTO pelanggan (nama_pelanggan, no_telp, email, alamat, tipe_pelanggan) VALUES 
('Toko ABC', '081234567890', 'toko@abc.com', 'Jl. Raya No. 123', 'grosir'),
('Budi Santoso', '085678901234', 'budi@email.com', 'Jl. Merdeka No. 45', 'retail'),
('Siti Nur', '082345678901', 'siti@email.com', 'Jl. Gatot Subroto No. 78', 'member');
