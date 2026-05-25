<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><?php echo $title; ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Periode</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" id="date_from" class="form-control" value="<?php echo $date_from; ?>">
                </div>
                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" id="date_to" class="form-control" value="<?php echo $date_to; ?>">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block" onclick="filterBestSeller()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Top 20 Produk Best Seller</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Kode Produk</th>
                        <th>Total Qty Terjual</th>
                        <th>Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($best_seller as $bs): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $bs['nama_produk']; ?></td>
                            <td><?php echo $bs['kode_produk']; ?></td>
                            <td><strong><?php echo $bs['total_qty']; ?> unit</strong></td>
                            <td>Rp <?php echo number_format($bs['total_revenue'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterBestSeller() {
        let date_from = document.getElementById('date_from').value;
        let date_to = document.getElementById('date_to').value;
        window.location.href = '<?php echo site_url("laporan/best_seller"); ?>?date_from=' + date_from + '&date_to=' + date_to;
    }
</script>

<?php $this->load->view('template/footer'); ?>