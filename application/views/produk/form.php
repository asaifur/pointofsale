<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-<?php echo ($action == 'tambah' ? 'plus' : 'edit'); ?>"></i>
                <?php echo ($action == 'tambah' ? 'Tambah' : 'Edit'); ?> Produk
            </h1>
        </div>
    </div>

    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-md-12">
            <a href="<?php echo site_url('produk'); ?>" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-form"></i> Form <?php echo ($action == 'tambah' ? 'Tambah' : 'Edit'); ?> Produk
            </h6>
        </div>
        <div class="card-body">
            <!-- Form Validation Errors -->
            <?php if (validation_errors()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> Mohon periksa kembali:
                    <ul class="mt-2 mb-0">
                        <?php echo validation_errors('<li>', '</li>'); ?>
                    </ul>
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="POST"
                action="<?php echo site_url('produk/' . ($action == 'tambah' ? 'simpan' : 'update/' . $produk['id_produk'])); ?>"
                class="needs-validation">

                <div class="row">
                    <!-- Kategori -->
                    <div class="col-md-6 mb-3">
                        <label for="id_kategori" class="form-label">
                            <strong>Kategori <span class="text-danger">*</span></strong>
                        </label>
                        <select name="id_kategori" id="id_kategori" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori as $k): ?>
                                <option value="<?php echo $k['id_kategori']; ?>"
                                    <?php echo (isset($produk) && $produk['id_kategori'] == $k['id_kategori']) ? 'selected' : ''; ?>>
                                    <?php echo $k['nama_kategori']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih kategori produk</small>
                    </div>

                    <!-- Kode Produk -->
                    <div class="col-md-6 mb-3">
                        <label for="kode_produk" class="form-label">
                            <strong>Kode Produk <span class="text-danger">*</span></strong>
                        </label>
                        <input type="text" name="kode_produk" id="kode_produk" class="form-control"
                            value="<?php echo isset($produk) ? $produk['kode_produk'] : ''; ?>"
                            placeholder="Contoh: PRD-001"
                            <?php echo ($action == 'edit') ? 'readonly' : 'required'; ?>>
                        <small class="form-text text-muted">Kode unik produk (tidak dapat diubah)</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Nama Produk -->
                    <div class="col-md-12 mb-3">
                        <label for="nama_produk" class="form-label">
                            <strong>Nama Produk <span class="text-danger">*</span></strong>
                        </label>
                        <input type="text" name="nama_produk" id="nama_produk" class="form-control"
                            value="<?php echo isset($produk) ? $produk['nama_produk'] : ''; ?>"
                            placeholder="Masukkan nama produk"
                            required>
                        <small class="form-text text-muted">Nama lengkap produk</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Deskripsi -->
                    <div class="col-md-12 mb-3">
                        <label for="deskripsi" class="form-label">
                            <strong>Deskripsi</strong>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="3"
                            placeholder="Masukkan deskripsi produk"><?php echo isset($produk) ? $produk['deskripsi'] : ''; ?></textarea>
                        <small class="form-text text-muted">Deskripsi singkat produk (opsional)</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Harga Beli -->
                    <div class="col-md-6 mb-3">
                        <label for="harga_beli" class="form-label">
                            <strong>Harga Beli (Rp) <span class="text-danger">*</span></strong>
                        </label>
                        <input type="number" name="harga_beli" id="harga_beli" class="form-control"
                            value="<?php echo isset($produk) ? $produk['harga_beli'] : ''; ?>"
                            placeholder="0"
                            min="0"
                            step="100"
                            required
                            onchange="hitungMargin()">
                        <small class="form-text text-muted">Harga beli dari supplier</small>
                    </div>

                    <!-- Harga Jual -->
                    <div class="col-md-6 mb-3">
                        <label for="harga_jual" class="form-label">
                            <strong>Harga Jual (Rp) <span class="text-danger">*</span></strong>
                        </label>
                        <input type="number" name="harga_jual" id="harga_jual" class="form-control"
                            value="<?php echo isset($produk) ? $produk['harga_jual'] : ''; ?>"
                            placeholder="0"
                            min="0"
                            step="100"
                            required
                            onchange="hitungMargin()">
                        <small class="form-text text-muted">Harga jual ke pelanggan</small>
                    </div>
                </div>

                <!-- Margin Info -->
                <div class="row">
                    <div class="col-md-12 mb-4">
                        <div class="card bg-light border-primary">
                            <div class="card-body">
                                <h6 class="card-title">Analisis Margin</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Margin: </strong>
                                        <span id="margin_rp" class="text-success">Rp 0</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Margin %: </strong>
                                        <span id="margin_persen" class="text-success">0%</span>
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Status: </strong>
                                        <span id="status_margin" class="badge badge-warning">Isi harga terlebih dahulu</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i>
                            <?php echo ($action == 'tambah' ? 'Tambah Produk' : 'Simpan Perubahan'); ?>
                        </button>
                        <a href="<?php echo site_url('produk'); ?>" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function hitungMargin() {
        const hargaBeli = parseFloat(document.getElementById('harga_beli').value) || 0;
        const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;

        const margin = hargaJual - hargaBeli;
        const marginPersen = hargaBeli > 0 ? (margin / hargaBeli) * 100 : 0;

        // Format display
        document.getElementById('margin_rp').textContent = 'Rp ' + margin.toLocaleString('id-ID');
        document.getElementById('margin_persen').textContent = marginPersen.toFixed(1) + '%';

        // Status
        let status = 'badge-warning';
        let statusText = 'Belum ada margin';

        if (margin > 0) {
            status = 'badge-success';
            statusText = 'Menguntungkan';
        } else if (margin < 0) {
            status = 'badge-danger';
            statusText = 'Rugi';
        } else if (margin === 0 && hargaBeli > 0) {
            status = 'badge-info';
            statusText = 'Break Even';
        }

        document.getElementById('status_margin').className = 'badge ' + status;
        document.getElementById('status_margin').textContent = statusText;
    }

    // Hitung margin saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        hitungMargin();
    });
</script>

<?php $this->load->view('template/footer'); ?>