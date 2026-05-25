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
                    <button class="btn btn-primary btn-block" onclick="filterPeriode()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button class="btn btn-success btn-block" onclick="exportPeriode()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan Periode <?php echo date('d/m/Y', strtotime($date_from)); ?> - <?php echo date('d/m/Y', strtotime($date_to)); ?></h3>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <?php
                $total_transaksi = 0;
                $total_penjualan = 0;
                $total_diskon = 0;
                $total_pajak = 0;
                $total_pendapatan = 0;

                foreach ($laporan as $lap) {
                    $total_transaksi += $lap['total_transaksi'];
                    $total_penjualan += $lap['total_penjualan'];
                    $total_diskon += $lap['total_diskon'];
                    $total_pajak += $lap['total_pajak'];
                    $total_pendapatan += $lap['total_pendapatan'];
                }
                ?>
                <div class="col-md-3">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3><?php echo $total_transaksi; ?></h3>
                            <p>Total Transaksi</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($total_penjualan, 0, ',', '.'); ?></h3>
                            <p>Total Penjualan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($total_diskon, 0, ',', '.'); ?></h3>
                            <p>Total Diskon</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-tag"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="small-box bg-primary">
                        <div class="inner">
                            <h3>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></h3>
                            <p>Total Pendapatan</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Transaksi</th>
                        <th>Penjualan</th>
                        <th>Diskon</th>
                        <th>Pajak</th>
                        <th>Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($laporan as $lap): ?>
                        <tr>
                            <td><strong><?php echo date('d/m/Y', strtotime($lap['tgl'])); ?></strong></td>
                            <td><?php echo $lap['total_transaksi']; ?></td>
                            <td>Rp <?php echo number_format($lap['total_penjualan'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($lap['total_diskon'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($lap['total_pajak'], 0, ',', '.'); ?></td>
                            <td><strong>Rp <?php echo number_format($lap['total_pendapatan'], 0, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterPeriode() {
        let date_from = document.getElementById('date_from').value;
        let date_to = document.getElementById('date_to').value;
        window.location.href = '<?php echo site_url("laporan/periode"); ?>?date_from=' + date_from + '&date_to=' + date_to;
    }

    function exportPeriode() {
        let date_from = document.getElementById('date_from').value;
        let date_to = document.getElementById('date_to').value;
        window.location.href = '<?php echo site_url("laporan/export_csv/periode"); ?>?date_from=' + date_from + '&date_to=' + date_to;
    }
</script>

<?php $this->load->view('template/footer'); ?>