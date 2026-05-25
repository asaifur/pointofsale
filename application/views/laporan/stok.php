<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><?php echo $title; ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Status Stok Produk</h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($nilai_stok, 0, ',', '.'); ?></h3>
                            <p>Total Nilai Stok</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-cube"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3><?php echo count($stok_habis); ?></h3>
                            <p>Produk Stok Menipis (≤5)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($stok_habis) > 0): ?>
        <div class="card mt-3 card-danger">
            <div class="card-header">
                <h3 class="card-title">Produk Stok Menipis</h3>
            </div>
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Stok Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stok_habis as $s): ?>
                            <tr>
                                <td><?php echo $s['kode_produk']; ?></td>
                                <td><?php echo $s['nama_produk']; ?></td>
                                <td><span class="badge badge-danger"><?php echo $s['stok_akhir']; ?></span></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="reorderProduk(<?php echo $s['id_produk']; ?>)">
                                        <i class="fas fa-plus"></i> Reorder
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Detail Stok Semua Produk</h3>
        </div>
        <div class="card-body" style="overflow-x: auto;">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Kode Produk</th>
                        <th>Nama Produk</th>
                        <th>Stok Awal</th>
                        <th>Stok Masuk</th>
                        <th>Stok Keluar</th>
                        <th>Stok Akhir</th>
                        <th>Harga Beli</th>
                        <th>Nilai Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stok as $s): ?>
                        <tr>
                            <td><?php echo $s['kode_produk']; ?></td>
                            <td><?php echo $s['nama_produk']; ?></td>
                            <td><?php echo $s['stok_awal']; ?></td>
                            <td><span class="badge badge-success"><?php echo $s['stok_masuk']; ?></span></td>
                            <td><span class="badge badge-danger"><?php echo $s['stok_keluar']; ?></span></td>
                            <td><strong><?php echo $s['stok_akhir']; ?></strong></td>
                            <td>Rp <?php echo number_format($s['harga_beli'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($s['stok_akhir'] * $s['harga_beli'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function reorderProduk(id_produk) {
        alert('Fitur reorder akan diintegrasikan dengan supplier');
    }
</script>

<?php $this->load->view('template/footer'); ?>