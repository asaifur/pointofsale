<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><?php echo $title; ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan Harian</h3>
            <div class="card-tools">
                <input type="date" id="filter_date" class="form-control" value="<?php echo $date; ?>" onchange="filterDate()">
            </div>
        </div>
        <div class="card-body">
            <?php if (!empty($laporan)): ?>
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
            <?php endif; ?>

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
    function filterDate() {
        let date = document.getElementById('filter_date').value;
        window.location.href = '<?php echo site_url("laporan/harian"); ?>?date=' + date;
    }
</script>

<?php $this->load->view('template/footer'); ?>