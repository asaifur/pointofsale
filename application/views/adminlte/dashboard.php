<?php
$user = $this->session->userdata();

?>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<div class="row">
    <div class="col-md-12">
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <?php $total_jobdesk = $this->Halal_model->getCountJobdesk(); ?>
                                <h3><?= $total_jobdesk ?></h3>

                                <p>Jobdesk</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                            <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline">
            <div class="card-header">
                <h3 class="card-title"> <i class="fas fa-chart-pie mr-1"></i>Daftar Pemilik Usaha</h3>

            </div>
            <div class="card-body">

                <div id="myfirstchart" style="height: 250px;"></div>
            </div>
            <div class="card-header">
                <h3 class="card-title"> <i class="fas fa-chart-pie mr-1"></i> Approve Jobdesk</h3>

            </div>
            <div class="card-body">
                <div id="myfirstchart2" style="height: 250px;"></div>
            </div>

            <?php if ($user['role'] == "3") { ?>
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Fee Bulanan Staff</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-4">
                            <select id="filter_bulan" class="form-control">
                                <option value="">-- Pilih Bulan --</option>
                                <option value="2026-01">Januari 2026</option>
                                <option value="2026-02">Februari 2026</option>
                                <option value="2026-03">Maret 2026</option>
                                <option value="2026-04">April 2026</option>
                                <option value="2026-05">Mei 2026</option>
                                <option value="2026-06">Juni 2026</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="btnFilterBulan" class="btn btn-primary">
                                Filter
                            </button>
                        </div>
                        <br>
                        <div id="table_gaji_container" style="height: 250px;"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
            <?php } else { ?>
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Fee Bulanan Staff</h3>
                    </div>
                    <div class="card-body">
                        <div class="col-md-4">
                            <select id="filter_bulan_karyawan" class="form-control">
                                <option value="">-- Pilih Bulan --</option>
                                <option value="2026-01">Januari 2026</option>
                                <option value="2026-02">Februari 2026</option>
                                <option value="2026-03">Maret 2026</option>
                                <option value="2026-04">April 2026</option>
                                <option value="2026-05">Mei 2026</option>
                                <option value="2026-06">Juni 2026</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button id="btnFilterBulan_karyawan" class="btn btn-primary">
                                Filter
                            </button>
                        </div>
                        <br>
                        <div id="table_gaji_karyawan" style="height: 250px;"></div>
                    </div>
                    <!-- /.card-body -->
                </div>
            <?php }; ?>
        </div>
    </div>
</div>


<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    $(function() {
        $.ajax({
            url: "<?= base_url('dashboard/chartJobdesk'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {

                // Cek jika data kosong
                if (data.length === 0) {
                    $('#myfirstchart').html(
                        '<p class="text-center">Data belum tersedia</p>'
                    );
                    return;
                }

                // Render chart jika ada data
                new Morris.Line({
                    element: 'myfirstchart',
                    data: data,
                    xkey: 'tanggal',
                    ykeys: ['total'],
                    labels: ['Jumlah Jobdesk'],
                    lineWidth: 2,
                    parseTime: false,
                    resize: true
                });

            },
            error: function() {
                $('#myfirstchart').html(
                    '<p class="text-danger text-center">Gagal mengambil data</p>'
                );
            }
        });
    });
</script>

<script>
    $('#dashboard_gaji').load("<?= base_url("dashboard/gaji_bulanan") ?>");
    $(function() {
        $.ajax({
            url: "<?= base_url('dashboard/chartPemilikUsahaBulanIni'); ?>",
            method: "GET",
            dataType: "json",
            success: function(data) {

                if (data.length === 0) {
                    $('#myfirstchart2').html(
                        '<p class="text-center">Belum ada pendaftar bulan ini</p>'
                    );
                    return;
                }

                new Morris.Line({
                    element: 'myfirstchart',
                    data: data,
                    xkey: 'tanggal',
                    ykeys: ['total'],
                    labels: ['Jumlah Pendaftar'],
                    lineWidth: 2,
                    parseTime: false,
                    resize: true,
                    lineColors: ['#17a2b8']
                });
            },
            error: function() {
                $('#myfirstchart2').html(
                    '<p class="text-danger text-center">Gagal mengambil data</p>'
                );
            }
        });
    });
</script>
<script>
    $(document).ready(function() {


        let startDate = '';
        let endDate = '';

        // Buka modal saat klik filter
        $('#btnFilterGaji').click(function() {
            $('#modalFilterGaji').modal('show');
        });

        // Init daterange
        $('#daterange_gaji').daterangepicker({
            opens: 'left',
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end) {
            startDate = start.format('YYYY-MM-DD');
            endDate = end.format('YYYY-MM-DD');
        });

        // Klik Terapkan

        $(document).ready(function() {

            $('#btnFilterBulan').click(function() {

                let bulan = $('#filter_bulan').val();

                if (bulan === '') {
                    alert('Pilih bulan dulu');
                    return;
                }

                loadTableGaji(bulan);

            });

        });


        function loadTableGaji(bulan = '') {

            $.ajax({
                url: "<?= base_url('dashboard/gajiTable'); ?>",
                method: "GET",
                data: {
                    bulan: bulan
                },
                success: function(response) {
                    $('#table_gaji_container').html(response);
                }
            });

        }
        $(document).ready(function() {

            $('#btnFilterBulan_karyawan').click(function() {

                let bulan = $('#filter_bulan_karyawan').val();

                if (bulan === '') {
                    alert('Pilih bulan dulu');
                    return;
                }

                loadTableGajikaryawan(bulan);

            });

        });


        function loadTableGajikaryawan(bulan = '') {

            $.ajax({
                url: "<?= base_url('dashboard/gajiTablekaryawan/'); ?>",
                method: "GET",
                data: {
                    bulan: bulan
                },
                success: function(response) {
                    $('#table_gaji_karyawan').html(response);
                }
            });

        }


    });
</script>

<?php $this->load->view('template/scriptes.php') ?>