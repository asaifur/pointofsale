<?php $this->load->view('template/header'); ?>

<div class="container-fluid">
    <!-- Page Title -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-chart-line"></i> Dashboard Admin POS
            </h1>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row">
        <!-- Total Transaksi Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-primary text-uppercase mb-1">
                        <small class="font-weight-bold">Total Transaksi Hari Ini</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_transaksi_today; ?>
                    </div>
                    <small class="text-muted">Transaksi</small>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?php echo site_url('sales/daftar'); ?>" class="small text-primary font-weight-bold">
                        Lihat Detail →
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Hari Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-success text-uppercase mb-1">
                        <small class="font-weight-bold">Total Penjualan Hari Ini</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        Rp <?php echo number_format($total_penjualan_today, 0, ',', '.'); ?>
                    </div>
                    <small class="text-muted">Omzet hari ini</small>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?php echo site_url('laporan/harian'); ?>" class="small text-success font-weight-bold">
                        Laporan Harian →
                    </a>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Bulan Ini -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">
                        <small class="font-weight-bold">Total Bulan Ini</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        Rp <?php echo number_format($total_bulan, 0, ',', '.'); ?>
                    </div>
                    <small class="text-muted"><?php echo $trx_bulan; ?> transaksi</small>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?php echo site_url('laporan/bulanan'); ?>" class="small text-info font-weight-bold">
                        Laporan Bulanan →
                    </a>
                </div>
            </div>
        </div>

        <!-- Stok Menipis -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-warning text-uppercase mb-1">
                        <small class="font-weight-bold">Stok Menipis</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?php echo $stok_habis; ?>
                    </div>
                    <small class="text-muted">Produk alert</small>
                </div>
                <div class="card-footer bg-light">
                    <a href="<?php echo site_url('laporan/stok'); ?>" class="small text-warning font-weight-bold">
                        Lihat Stok →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="row mb-4">
        <!-- Total Produk -->
        <div class="col-md-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-secondary text-uppercase mb-1">
                        <small class="font-weight-bold">Total Produk</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_produk; ?>
                    </div>
                    <small class="text-muted">Produk aktif</small>
                </div>
            </div>
        </div>

        <!-- Total Pelanggan -->
        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-info text-uppercase mb-1">
                        <small class="font-weight-bold">Total Pelanggan</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        <?php echo $total_pelanggan; ?>
                    </div>
                    <small class="text-muted">Pelanggan terdaftar</small>
                </div>
            </div>
        </div>

        <!-- Nilai Inventory -->
        <div class="col-md-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="text-danger text-uppercase mb-1">
                        <small class="font-weight-bold">Nilai Inventory</small>
                    </div>
                    <div class="h3 mb-0 font-weight-bold text-gray-800">
                        Rp <?php echo number_format($nilai_stok, 0, ',', '.'); ?>
                    </div>
                    <small class="text-muted">Total stok value</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- Sales Chart 7 Days -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line"></i> Penjualan 7 Hari Terakhir
                    </h6>
                    <a href="<?php echo site_url('laporan/periode'); ?>" class="btn btn-sm btn-outline-primary">
                        Detail Laporan
                    </a>
                </div>
                <div class="card-body">
                    <canvas id="chartSales7Days" style="max-height: 400px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Category Distribution -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pie-chart"></i> Penjualan per Kategori (Hari Ini)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartCategory" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Charts Row -->
    <div class="row mb-4">
        <!-- Hourly Sales -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-clock"></i> Penjualan Per Jam (Hari Ini)
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartHourlySales" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star"></i> Top 5 Produk Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <canvas id="chartTopProducts" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Sellers Table -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-fire"></i> Best Sellers Hari Ini
                    </h6>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($best_sellers)): ?>
                                <?php foreach ($best_sellers as $seller): ?>
                                    <tr>
                                        <td><?php echo $seller['nama_produk']; ?></td>
                                        <td><span class="badge badge-primary"><?php echo $seller['total_qty']; ?></span></td>
                                        <td>Rp <?php echo number_format($seller['total'], 0, ',', '.'); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center text-muted">Belum ada data</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt"></i> Transaksi Terbaru
                    </h6>
                    <a href="<?php echo site_url('sales/daftar'); ?>" class="btn btn-sm btn-outline-primary">
                        Lihat Semua
                    </a>
                </div>
                <div class="card-body">
                    <div style="max-height: 350px; overflow-y: auto;">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_transactions)): ?>
                                    <?php foreach ($recent_transactions as $trx): ?>
                                        <tr>
                                            <td>
                                                <small><strong><?php echo substr($trx['no_transaksi'], 0, 12); ?></strong></small>
                                            </td>
                                            <td>
                                                <small>Rp <?php echo number_format($trx['total_harga'], 0, ',', '.'); ?></small>
                                            </td>
                                            <td>
                                                <?php
                                                $status_class = 'badge-secondary';
                                                if ($trx['status'] == 'selesai') $status_class = 'badge-success';
                                                if ($trx['status'] == 'pending') $status_class = 'badge-warning';
                                                if ($trx['status'] == 'batal') $status_class = 'badge-danger';
                                                ?>
                                                <span class="badge <?php echo $status_class; ?> badge-sm">
                                                    <?php echo $trx['status']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Belum ada transaksi</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt"></i> Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo site_url('sales'); ?>" class="btn btn-primary btn-block">
                                <i class="fas fa-cash-register"></i> Buka Kasir
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo site_url('laporan/harian'); ?>" class="btn btn-info btn-block">
                                <i class="fas fa-file-pdf"></i> Laporan Harian
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo site_url('laporan/stok'); ?>" class="btn btn-warning btn-block">
                                <i class="fas fa-warehouse"></i> Cek Stok
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="<?php echo site_url('laporan/best_seller'); ?>" class="btn btn-success btn-block">
                                <i class="fas fa-star"></i> Best Seller
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart 1: Sales 7 Days
        fetch('<?php echo site_url("dashboard/chart_sales_7days"); ?>')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('chartSales7Days').getContext('2d');
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(d => d.tanggal),
                        datasets: [{
                            label: 'Penjualan (Rp)',
                            data: data.map(d => d.total),
                            borderColor: '#4e73df',
                            backgroundColor: 'rgba(78, 115, 223, 0.05)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#4e73df',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            });

        // Chart 2: Category Distribution
        fetch('<?php echo site_url("dashboard/chart_sales_by_category"); ?>')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('chartCategory').getContext('2d');
                const colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74c3c', '#95a5a6'];
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: colors.slice(0, data.data.length)
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });

        // Chart 3: Hourly Sales
        fetch('<?php echo site_url("dashboard/chart_hourly_sales"); ?>')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('chartHourlySales').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.map(d => d.jam),
                        datasets: [{
                            label: 'Penjualan per Jam',
                            data: data.map(d => d.total),
                            backgroundColor: '#36b9cc',
                            borderColor: '#1b9b9e',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        indexAxis: 'x',
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + value.toLocaleString('id-ID');
                                    }
                                }
                            }
                        }
                    }
                });
            });

        // Chart 4: Top Products
        fetch('<?php echo site_url("dashboard/chart_top_products"); ?>')
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('chartTopProducts').getContext('2d');
                new Chart(ctx, {
                    type: 'horizontalBar',
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Jumlah Terjual',
                            data: data.data,
                            backgroundColor: '#1cc88a',
                            borderColor: '#0e8449',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            });
    });
</script>

<?php $this->load->view('template/footer'); ?>