<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-box"></i> Manajemen Produk
            </h1>
        </div>
        <div class="col-md-4 text-right">
            <a href="<?php echo site_url('produk/tambah'); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Produk
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if ($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($this->session->flashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Filter & Search -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="form-inline">
                <div class="form-group mr-2">
                    <input type="text" name="search" class="form-control form-control-sm"
                        placeholder="Cari produk..." value="<?php echo $search; ?>">
                </div>
                <div class="form-group mr-2">
                    <select name="kategori" class="form-control form-control-sm">
                        <option value="">-- Semua Kategori --</option>
                        <?php foreach ($kategori as $k): ?>
                            <option value="<?php echo $k['id_kategori']; ?>"
                                <?php echo $kategori_filter == $k['id_kategori'] ? 'selected' : ''; ?>>
                                <?php echo $k['nama_kategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-info btn-sm">
                    <i class="fas fa-search"></i> Filter
                </button>
                <a href="<?php echo site_url('produk'); ?>" class="btn btn-secondary btn-sm ml-2">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Daftar Produk
                <span class="badge badge-primary ml-2"><?php echo count($produk); ?></span>
            </h6>
        </div>
        <div class="card-body">
            <?php if (empty($produk)): ?>
                <div class="alert alert-info text-center" role="alert">
                    <i class="fas fa-info-circle"></i> Belum ada data produk
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="produkTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="40" class="text-center">#</th>
                                <th>Kode</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th class="text-right">Harga Beli</th>
                                <th class="text-right">Harga Jual</th>
                                <th class="text-right">Margin</th>
                                <th width="120" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            foreach ($produk as $p): ?>
                                <?php
                                $margin = $p['harga_jual'] - $p['harga_beli'];
                                $margin_persen = ($margin / $p['harga_beli']) * 100;
                                ?>
                                <tr>
                                    <td class="text-center"><?php echo $no++; ?></td>
                                    <td>
                                        <span class="badge badge-info"><?php echo $p['kode_produk']; ?></span>
                                    </td>
                                    <td>
                                        <strong><?php echo $p['nama_produk']; ?></strong>
                                        <?php if ($p['deskripsi']): ?>
                                            <br><small class="text-muted"><?php echo substr($p['deskripsi'], 0, 50); ?>..</small>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $p['nama_kategori']; ?></td>
                                    <td class="text-right">
                                        <strong>Rp <?php echo number_format($p['harga_beli'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td class="text-right">
                                        <strong>Rp <?php echo number_format($p['harga_jual'], 0, ',', '.'); ?></strong>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge badge-success">
                                            <?php echo number_format($margin_persen, 1); ?>%
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="<?php echo site_url('produk/detail/' . $p['id_produk']); ?>"
                                                class="btn btn-info" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo site_url('produk/edit/' . $p['id_produk']); ?>"
                                                class="btn btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Hapus"
                                                onclick="hapus(<?php echo $p['id_produk']; ?>, '<?php echo $p['nama_produk']; ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Confirm Delete Modal -->
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function hapus(id, nama) {
        $('#produk_name').text(nama);
        $('#hapus_link').attr('href', '<?php echo site_url("produk/hapus/"); ?>' + id);
        $('#deleteModal').modal('show');
    }

    // Initialize DataTable
    $(document).ready(function() {
        $('#produkTable').DataTable({
            language: {
                url: 'https://cdn.datatables.net/plug-ins/1.11.3/i18n/id.json'
            },
            order: [
                [1, 'asc']
            ],
            pageLength: 25
        });
    });
</script>

<?php $this->load->view('template/footer'); ?>