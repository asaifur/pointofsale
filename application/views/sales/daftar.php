<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><?php echo $title; ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Transaksi Penjualan</h3>
            <div class="card-tools">
                <a href="<?php echo site_url('sales/index'); ?>" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Transaksi Baru
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Tanggal</th>
                        <th>Item</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($penjualan as $p): ?>
                        <tr>
                            <td><strong><?php echo $p['no_transaksi']; ?></strong></td>
                            <td><?php echo $p['nama_pelanggan'] ?? 'Umum'; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($p['tgl_penjualan'])); ?></td>
                            <td><?php echo $p['total_items']; ?></td>
                            <td>Rp. <?php echo number_format($p['total_harga'], 0, ',', '.'); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo ucfirst($p['metode_bayar']); ?></span>
                            </td>
                            <td>
                                <?php
                                $status_class = 'badge-secondary';
                                if ($p['status'] == 'selesai') $status_class = 'badge-success';
                                if ($p['status'] == 'pending') $status_class = 'badge-warning';
                                if ($p['status'] == 'batal') $status_class = 'badge-danger';
                                ?>
                                <span class="badge <?php echo $status_class; ?>"><?php echo ucfirst($p['status']); ?></span>
                            </td>
                            <td>
                                <a href="<?php echo site_url('sales/detail/' . $p['id_penjualan']); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Lihat
                                </a>
                                <a href="<?php echo site_url('sales/cetak_struk/' . $p['id_penjualan']); ?>" class="btn btn-sm btn-primary" target="_blank">
                                    <i class="fas fa-print"></i> Cetak
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $this->load->view('template/footer'); ?>