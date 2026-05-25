<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Transaksi - <?php echo $penjualan['no_transaksi']; ?></h3>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>No. Transaksi:</strong> <?php echo $penjualan['no_transaksi']; ?></p>
                            <p><strong>Tanggal:</strong> <?php echo date('d/m/Y H:i', strtotime($penjualan['tgl_penjualan'])); ?></p>
                            <p><strong>Pelanggan:</strong> <?php echo $penjualan['nama_pelanggan'] ?? 'Umum'; ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Metode Bayar:</strong> <?php echo ucfirst($penjualan['metode_bayar']); ?></p>
                            <p><strong>Status:</strong>
                                <?php
                                $status_class = 'badge-secondary';
                                if ($penjualan['status'] == 'selesai') $status_class = 'badge-success';
                                if ($penjualan['status'] == 'pending') $status_class = 'badge-warning';
                                if ($penjualan['status'] == 'batal') $status_class = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($penjualan['status']); ?></span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h5>Item Penjualan</h5>
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kode</th>
                                <th>Qty</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($detail as $d): ?>
                                <tr>
                                    <td><?php echo $d['nama_produk']; ?></td>
                                    <td><?php echo $d['kode_produk']; ?></td>
                                    <td><?php echo $d['qty']; ?></td>
                                    <td>Rp. <?php echo number_format($d['harga_satuan'], 0, ',', '.'); ?></td>
                                    <td>Rp. <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Ringkasan Pembayaran</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-6">Subtotal:</div>
                        <div class="col-6 text-right">Rp. <?php echo number_format($penjualan['subtotal'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Diskon:</div>
                        <div class="col-6 text-right">- Rp. <?php echo number_format($penjualan['diskon'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">Pajak:</div>
                        <div class="col-6 text-right">+ Rp. <?php echo number_format($penjualan['pajak'], 0, ',', '.'); ?></div>
                    </div>
                    <hr>
                    <div class="row mb-3">
                        <div class="col-6">
                            <h5>Total:</h5>
                        </div>
                        <div class="col-6 text-right">
                            <h5>Rp. <?php echo number_format($penjualan['total_harga'], 0, ',', '.'); ?></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="row mb-2">
                        <div class="col-6">Bayar:</div>
                        <div class="col-6 text-right">Rp. <?php echo number_format($penjualan['jumlah_bayar'], 0, ',', '.'); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">Kembalian:</div>
                        <div class="col-6 text-right"><strong class="text-success">Rp. <?php echo number_format($penjualan['kembalian'], 0, ',', '.'); ?></strong></div>
                    </div>

                    <a href="<?php echo site_url('sales/cetak_struk/' . $penjualan['id_penjualan']); ?>" class="btn btn-primary btn-block" target="_blank">
                        <i class="fas fa-print"></i> Cetak Struk
                    </a>
                    <a href="<?php echo site_url('sales/daftar'); ?>" class="btn btn-secondary btn-block mt-2">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>