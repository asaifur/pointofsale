<div class="card">
    <div class="card-header border-0">

        <div class="card-tools">
            <a href="#" class="btn btn-tool btn-sm">
                <i class="fas fa-download"></i>
            </a>
        </div>
    </div>

    <div class="card-body table-responsive p-0">
        <table class="table table-striped table-valign-middle">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Harga / Job</th>
                    <th>Total Jobdesk (QC=1)</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                <?php $hargaPerJob = 12000;
                $totalGaji = $hargaPerJob * $result->total_jobdesk; ?>
                <tr>
                    <td>
                        <img src="<?= base_url('assets/dist/img/avatar.png') ?>"
                            class="img-circle img-size-32 mr-2">
                        <?= $result->username ?>
                    </td>

                    <td>
                        Rp <?= number_format($hargaPerJob, 0, ',', '.') ?>
                    </td>

                    <td>
                        <small class="text-success mr-1">
                            <i class="fas fa-check-circle"></i>
                        </small>
                        <?= $result->total_jobdesk ?> Job
                    </td>

                    <td>
                        <strong class="text-primary">
                            Rp <?= number_format($totalGaji, 0, ',', '.') ?>
                        </strong>
                    </td>
                </tr>



            </tbody>
        </table>
    </div>
</div>