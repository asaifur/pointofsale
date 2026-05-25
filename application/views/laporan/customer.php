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
                    <button class="btn btn-primary btn-block" onclick="filterCustomer()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan per Customer</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Customer</th>
                        <th>Total Transaksi</th>
                        <th>Total Belanja</th>
                        <th>Rata-rata Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($customer as $c): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo $c['nama_pelanggan']; ?></td>
                            <td><?php echo $c['total_transaksi']; ?></td>
                            <td><strong>Rp <?php echo number_format($c['total_belanja'], 0, ',', '.'); ?></strong></td>
                            <td>Rp <?php echo number_format($c['total_belanja'] / $c['total_transaksi'], 0, ',', '.'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterCustomer() {
        let date_from = document.getElementById('date_from').value;
        let date_to = document.getElementById('date_to').value;
        window.location.href = '<?php echo site_url("laporan/customer"); ?>?date_from=' + date_from + '&date_to=' + date_to;
    }
</script>

<?php $this->load->view('template/footer'); ?>