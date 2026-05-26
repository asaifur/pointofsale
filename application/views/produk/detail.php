<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box"></i> Detail Produk
            </h1>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="<?php echo site_url('produk'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="<?php echo site_url('produk/edit/' . $produk['id_produk']); ?>" class="btn btn-warning btn-sm">
                <i class="fas fa-edit"></i> Edit
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Detail Card -->
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle"></i> Informasi Produk
                    </h6>
                </div>
                <div class="card-body">
                    <!-- Kode & Nama -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h4 class="mb-0"><?php echo $produk['nama_produk']; ?></h4>
                            <small class="text-muted">
                                Kode: <span class="badge badge-info"><?php echo $produk['kode_produk']; ?></span>
                            </small>
                        </div>
                    </div>

                    <!-- Kategori -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Kategori:</strong>
                            <p class="text-gray-800">
                                <i class="fas fa-tag"></i> <?php echo $produk['nama_kategori']; ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong>
                            <p>
                                <?php if ($produk['is_active'] == 1): ?>
                                    <span class="badge badge-success"><i class="fas fa-check"></i> Aktif</span>
                                <?php else: ?>
                                    <span class="badge badge-danger"><i class="fas fa-times"></i> Tidak Aktif</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>

                    <!-- Deskripsi -->
                    <?php if ($produk['deskripsi']): ?>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <strong>Deskripsi:</strong>
                                <p class="text-gray-800"><?php echo nl2br($produk['deskripsi']); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <!-- Harga Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong><i class="fas fa-arrow-down text-danger"></i> Harga Beli:</strong>
                            <h5 class="text-danger mb-0">
                                Rp <?php echo number_format($produk['harga_beli'], 0, ',', '.'); ?>
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <strong><i class="fas fa-arrow-up text-success"></i> Harga Jual:</strong>
                            <h5 class="text-success mb-0">
                                Rp <?php echo number_format($produk['harga_jual'], 0, ',', '.'); ?>
                            </h5>
                        </div>
                    </div>

                    <!-- Margin -->
                    <?php
                    $margin = $produk['harga_jual'] - $produk['harga_beli'];
                    $margin_persen = ($margin / $produk['harga_beli']) * 100;
                    ?>
                    <div class="row">
                        <div class="col-md-6">
                            <strong><i class="fas fa-calculator text-info"></i> Margin:</strong>
                            <h5 class="text-info mb-0">
                                Rp <?php echo number_format($margin, 0, ',', '.'); ?>
                            </h5>
                        </div>
                        <div class="col-md-6">
                            <strong>Margin %:</strong>
                            <h5 class="mb-0">
                                <span class="badge badge-lg badge-success">
                                    <?php echo number_format($margin_persen, 2); ?>%
                                </span>
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Dates -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-calendar"></i> Tanggal
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Dibuat:</strong>
                        <p class="text-muted mb-0">
                            <?php echo date('d M Y H:i', strtotime($produk['created_at'])); ?>
                        </p>
                    </div>
                    <div>
                        <strong>Diubah:</strong>
                        <p class="text-muted mb-0">
                            <?php echo date('d M Y H:i', strtotime($produk['updated_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog"></i> Aksi
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="<?php echo site_url('produk/edit/' . $produk['id_produk']); ?>"
                            class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Edit Produk
                        </a>
                        <button type="button"
                            class="btn btn-danger btn-block"
                            onclick="hapusProduk(<?php echo $produk['id_produk']; ?>, '<?php echo $produk['nama_produk']; ?>')">
                            <i class="fas fa-trash"></i> Hapus Produk
                        </button>
                    </div>
                </div>
            </div>

            <!-- Price Analysis -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar"></i> Analisis Harga
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tr>
                            <td>Harga Beli</td>
                            <td class="text-right">
                                Rp <?php echo number_format($produk['harga_beli'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <tr class="table-active">
                            <td><strong>Margin Rp</strong></td>
                            <td class="text-right"><strong>Rp <?php echo number_format($margin, 0, ',', '.'); ?></strong></td>
                        </tr>
                        <tr>
                            <td>Harga Jual</td>
                            <td class="text-right">
                                <strong>Rp <?php echo number_format($produk['harga_jual'], 0, ',', '.'); ?></strong>
                            </td>
                        </tr>
                    </table>
                    <hr>
                    <p class="small mb-0">
                        <strong>Profitabilitas:</strong>
                        <span class="badge badge-success">
                            Margin <?php echo number_format($margin_persen, 2); ?>%
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-trash"></i> Hapus Produk</h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus produk <strong id="produk_name"></strong>?</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <a href="#" id="hapus_link" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function hapusProduk(id, nama) {
        $('#produk_name').text(nama);
        $('#hapus_link').attr('href', '<?php echo site_url("produk/hapus/"); ?>' + id);
        $('#deleteModal').modal('show');
    }
</script>

<?php $this->load->view('template/footer'); ?>