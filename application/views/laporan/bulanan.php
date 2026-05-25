<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-md-12">
            <h2><?php echo $title; ?></h2>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Filter Laporan</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label>Bulan</label>
                    <select id="bulan" class="form-control" value="<?php echo $bulan; ?>" onchange="filterBulan()">
                        <?php for ($i = 1; $i <= 12; $i++): ?>
                            <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>" <?php echo $i == $bulan ? 'selected' : ''; ?>>
                                <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Tahun</label>
                    <select id="tahun" class="form-control" value="<?php echo $tahun; ?>" onchange="filterBulan()">
                        <?php for ($i = 2020; $i <= date('Y'); $i++): ?>
                            <option value="<?php echo $i; ?>" <?php echo $i == $tahun ? 'selected' : ''; ?>>
                                <?php echo $i; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button class="btn btn-primary btn-block" onclick="exportCSV()">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-3">
        <div class="card-header">
            <h3 class="card-title">Laporan Penjualan Bulanan</h3>
        </div>
        <div class="card-body">
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
                    <?php
                    $total_transaksi = 0;
                    $total_penjualan = 0;
                    $total_diskon = 0;
                    $total_pajak = 0;
                    $total_pendapatan = 0;

                    foreach ($laporan as $lap):
                        $total_transaksi += $lap['total_transaksi'];
                        $total_penjualan += $lap['total_penjualan'];
                        $total_diskon += $lap['total_diskon'];
                        $total_pajak += $lap['total_pajak'];
                        $total_pendapatan += $lap['total_pendapatan'];
                    ?>
                        <tr>
                            <td><strong><?php echo date('d/m/Y', strtotime($lap['tgl'])); ?></strong></td>
                            <td><?php echo $lap['total_transaksi']; ?></td>
                            <td>Rp <?php echo number_format($lap['total_penjualan'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($lap['total_diskon'], 0, ',', '.'); ?></td>
                            <td>Rp <?php echo number_format($lap['total_pajak'], 0, ',', '.'); ?></td>
                            <td><strong>Rp <?php echo number_format($lap['total_pendapatan'], 0, ',', '.'); ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr style="border-top: 3px solid #000; font-weight: bold; background-color: #f5f5f5;">
                        <td>TOTAL</td>
                        <td><?php echo $total_transaksi; ?></td>
                        <td>Rp <?php echo number_format($total_penjualan, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($total_diskon, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($total_pajak, 0, ',', '.'); ?></td>
                        <td>Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function filterBulan() {
        let bulan = document.getElementById('bulan').value;
        let tahun = document.getElementById('tahun').value;
        window.location.href = '<?php echo site_url("laporan/bulanan"); ?>?bulan=' + bulan + '&tahun=' + tahun;
    }

    function exportCSV() {
        let bulan = document.getElementById('bulan').value;
        let tahun = document.getElementById('tahun').value;
        window.location.href = '<?php echo site_url("laporan/export_csv/periode"); ?>?date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>';
    }
</script>

<?php $this->load->view('template/footer'); ?>