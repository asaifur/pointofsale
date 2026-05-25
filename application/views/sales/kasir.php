<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <!-- BAGIAN PRODUK -->
        <div class="col-md-7">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pilih Produk</h3>
                    <div class="card-tools">
                        <input type="text" id="search_produk" class="form-control form-control-sm" placeholder="Cari produk..." style="width: 200px;">
                    </div>
                </div>
                <div class="card-body">
                    <div id="produk_list" class="row">
                        <?php foreach ($produk as $p): ?>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card product-card" onclick="tambahKeKeranjang(<?php echo $p['id_produk']; ?>)">
                                    <div class="card-body text-center p-2">
                                        <h6><?php echo $p['nama_produk']; ?></h6>
                                        <small class="text-muted"><?php echo $p['kode_produk']; ?></small>
                                        <p class="h5 text-primary mb-0 mt-2">Rp. <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- BAGIAN KASIR -->
        <div class="col-md-5">
            <!-- FORM INPUT -->
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Baru</h3>
                </div>
                <div class="card-body">
                    <input type="hidden" id="id_penjualan" value="">
                    <input type="hidden" id="no_transaksi" value="">

                    <div class="form-group">
                        <label>No. Transaksi</label>
                        <input type="text" id="display_transaksi" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label>Pelanggan</label>
                        <select id="id_pelanggan" class="form-control">
                            <option value="">-- Pilih Pelanggan (Opsional) --</option>
                            <?php foreach ($pelanggan as $p): ?>
                                <option value="<?php echo $p['id_pelanggan']; ?>"><?php echo $p['nama_pelanggan']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <input type="text" id="input_produk" class="form-control" placeholder="Ketik kode/nama produk atau klik produk di atas">
                        <span class="input-group-append">
                            <button type="button" class="btn btn-info" onclick="tambahProduk()">Tambah</button>
                        </span>
                    </div>
                </div>
            </div>

            <!-- KERANJANG -->
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title">Keranjang Belanja</h3>
                </div>
                <div class="card-body" style="max-height: 350px; overflow-y: auto;">
                    <table class="table table-sm table-hover" id="keranjang_table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                                <th>Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="keranjang_body">
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- RINGKASAN TOTAL -->
            <div class="card card-success">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">Subtotal:</div>
                        <div class="col-6 text-right"><strong id="subtotal">Rp. 0</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label>Diskon:</label>
                            <input type="number" id="diskon" class="form-control form-control-sm" value="0" onchange="updateTotal()">
                        </div>
                        <div class="col-6 text-right"><strong id="display_diskon">Rp. 0</strong></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <label>Pajak (%):</label>
                            <input type="number" id="pajak_persen" class="form-control form-control-sm" value="0" onchange="updateTotal()">
                        </div>
                        <div class="col-6 text-right"><strong id="display_pajak">Rp. 0</strong></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Total:</h5>
                        </div>
                        <div class="col-6 text-right">
                            <h5 id="total">Rp. 0</h5>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Metode Bayar</label>
                        <select id="metode_bayar" class="form-control">
                            <option value="tunai">Tunai</option>
                            <option value="kartu_kredit">Kartu Kredit</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="cek">Cek</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Jumlah Bayar</label>
                        <input type="number" id="jumlah_bayar" class="form-control" placeholder="0" onchange="updateKembalian()">
                    </div>

                    <div class="form-group">
                        <label>Kembalian:</label>
                        <h5 id="kembalian" class="text-success">Rp. 0</h5>
                    </div>

                    <button type="button" class="btn btn-success btn-block" onclick="checkout()">
                        <i class="fas fa-check"></i> CHECKOUT
                    </button>
                    <button type="button" class="btn btn-danger btn-block mt-2" onclick="resetTransaksi()">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart_items = [];
    let subtotal_total = 0;

    // INIT TRANSAKSI BARU
    document.addEventListener('DOMContentLoaded', function() {
        buatTransaksiBaru();
    });

    function buatTransaksiBaru() {
        fetch('<?php echo site_url("sales/buat_transaksi"); ?>')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('id_penjualan').value = data.id_penjualan;
                    document.getElementById('no_transaksi').value = data.no_transaksi;
                    document.getElementById('display_transaksi').value = data.no_transaksi;
                }
            });
    }

    // TAMBAH KE KERANJANG (DARI CLICK PRODUK)
    function tambahKeKeranjang(id_produk) {
        fetch('<?php echo site_url("sales/get_produk"); ?>/' + id_produk)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.stok > 0) {
                    simpanItem(data.data, 1);
                } else {
                    alert('Stok tidak tersedia');
                }
            });
    }

    // TAMBAH PRODUK VIA INPUT
    function tambahProduk() {
        let input = document.getElementById('input_produk').value;
        if (!input) {
            alert('Masukkan kode atau nama produk');
            return;
        }

        fetch('<?php echo site_url("sales/search_produk"); ?>?q=' + input)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    simpanItem(data[0], 1);
                    document.getElementById('input_produk').value = '';
                } else {
                    alert('Produk tidak ditemukan');
                }
            });
    }

    // SIMPAN ITEM KE KERANJANG
    function simpanItem(produk, qty = 1) {
        let id_penjualan = document.getElementById('id_penjualan').value;

        let formData = new FormData();
        formData.append('id_penjualan', id_penjualan);
        formData.append('id_produk', produk.id_produk);
        formData.append('qty', qty);

        fetch('<?php echo site_url("sales/tambah_item"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateKeranjang(data.detail);
                    updateTotal();
                } else {
                    alert(data.message);
                }
            });
    }

    // UPDATE TAMPILAN KERANJANG
    function updateKeranjang(detail) {
        let body = document.getElementById('keranjang_body');
        body.innerHTML = '';

        detail.forEach(item => {
            let row = document.createElement('tr');
            row.innerHTML = `
            <td>${item.nama_produk}</td>
            <td><input type="number" value="${item.qty}" class="form-control form-control-sm" style="width: 50px;"></td>
            <td>Rp. ${formatRupiah(item.harga_satuan)}</td>
            <td>Rp. ${formatRupiah(item.subtotal)}</td>
            <td><button class="btn btn-sm btn-danger" onclick="hapusItem(${item.id_detail})"><i class="fas fa-trash"></i></button></td>
        `;
            body.appendChild(row);
        });
    }

    // HAPUS ITEM
    function hapusItem(id_detail) {
        if (!confirm('Hapus item ini?')) return;

        let id_penjualan = document.getElementById('id_penjualan').value;
        let formData = new FormData();
        formData.append('id_detail', id_detail);
        formData.append('id_penjualan', id_penjualan);

        fetch('<?php echo site_url("sales/hapus_item"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    buatTransaksiBaru();
                }
            });
    }

    // UPDATE TOTAL
    function updateTotal() {
        let id_penjualan = document.getElementById('id_penjualan').value;

        fetch('<?php echo site_url("sales/get_produk"); ?>/' + id_penjualan)
            .then(response => response.json())
            .then(data => {
                // Fetch ulang detail
            });

        // Simplified calculation
        let diskon = parseFloat(document.getElementById('diskon').value) || 0;
        let pajak_persen = parseFloat(document.getElementById('pajak_persen').value) || 0;

        // TODO: Get subtotal from server
    }

    function updateKembalian() {
        let total = parseFloat(document.getElementById('total').textContent.replace(/[^0-9]/g, '')) || 0;
        let bayar = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
        let kembalian = bayar - total;

        document.getElementById('kembalian').textContent = 'Rp. ' + formatRupiah(kembalian);
    }

    // CHECKOUT
    function checkout() {
        let id_penjualan = document.getElementById('id_penjualan').value;
        let id_pelanggan = document.getElementById('id_pelanggan').value;
        let metode_bayar = document.getElementById('metode_bayar').value;
        let diskon = parseFloat(document.getElementById('diskon').value) || 0;
        let pajak_persen = parseFloat(document.getElementById('pajak_persen').value) || 0;
        let jumlah_bayar = parseFloat(document.getElementById('jumlah_bayar').value);

        if (!id_penjualan) {
            alert('Buat transaksi terlebih dahulu');
            return;
        }

        if (!jumlah_bayar) {
            alert('Masukkan jumlah bayar');
            return;
        }

        let formData = new FormData();
        formData.append('id_penjualan', id_penjualan);
        formData.append('id_pelanggan', id_pelanggan);
        formData.append('metode_bayar', metode_bayar);
        formData.append('diskon', diskon);
        formData.append('pajak', pajak_persen);
        formData.append('jumlah_bayar', jumlah_bayar);

        fetch('<?php echo site_url("sales/simpan_transaksi"); ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.open('<?php echo site_url("sales/cetak_struk"); ?>/' + data.id_penjualan);
                    resetTransaksi();
                } else {
                    alert(data.message);
                }
            });
    }

    // RESET TRANSAKSI
    function resetTransaksi() {
        document.getElementById('id_pelanggan').value = '';
        document.getElementById('diskon').value = 0;
        document.getElementById('pajak_persen').value = 0;
        document.getElementById('jumlah_bayar').value = '';
        document.getElementById('input_produk').value = '';
        document.getElementById('keranjang_body').innerHTML = '';
        buatTransaksiBaru();
    }

    // UTILITY
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
</script>

<style>
    .product-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #fff;
    }

    .product-card:hover {
        border-color: #007bff;
        box-shadow: 0 0 10px rgba(0, 123, 255, 0.3);
        transform: translateY(-5px);
    }
</style>

<?php $this->load->view('template/footer'); ?>