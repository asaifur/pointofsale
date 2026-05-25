<?php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>

<head>
    <title>Struk Penjualan - <?php echo $penjualan['no_transaksi']; ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }

        .struk {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }

        .header {
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .content {
            text-align: left;
        }

        .item {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
            padding: 2px 0;
            border-bottom: 1px dotted #999;
        }

        .footer {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
            text-align: center;
        }

        .total {
            font-weight: bold;
            font-size: 14px;
            margin: 5px 0;
        }

        .separator {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="struk">
        <div class="header">
            <h3>STRUK PENJUALAN</h3>
            <p>Toko ABC</p>
        </div>

        <div class="content">
            <div style="margin-bottom: 8px;">
                <strong><?php echo $penjualan['no_transaksi']; ?></strong><br>
                <small><?php echo date('d/m/Y H:i:s', strtotime($penjualan['tgl_penjualan'])); ?></small>
            </div>

            <div class="separator"></div>

            <div style="margin-bottom: 8px;">
                <strong>Pelanggan:</strong><br>
                <?php echo $penjualan['nama_pelanggan'] ?? 'Umum'; ?>
            </div>

            <div class="separator"></div>

            <table style="width: 100%; font-size: 11px;">
                <tr style="border-bottom: 1px dashed #999;">
                    <th style="text-align: left;">Item</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Harga</th>
                </tr>
                <?php foreach ($detail as $d): ?>
                    <tr>
                        <td><?php echo substr($d['nama_produk'], 0, 15); ?></td>
                        <td style="text-align: center;"><?php echo $d['qty']; ?></td>
                        <td style="text-align: right;">
                            <small>Rp <?php echo number_format($d['harga_satuan'], 0, ',', '.'); ?></small>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px dotted #ccc;">
                        <td colspan="3" style="text-align: right;">
                            <small>Rp <?php echo number_format($d['subtotal'], 0, ',', '.'); ?></small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

            <div class="separator"></div>

            <div style="text-align: right; margin: 5px 0;">
                <strong>Subtotal:</strong> Rp <?php echo number_format($penjualan['subtotal'], 0, ',', '.'); ?>
            </div>
            <?php if ($penjualan['diskon'] > 0): ?>
                <div style="text-align: right; margin: 5px 0;">
                    <strong>Diskon:</strong> - Rp <?php echo number_format($penjualan['diskon'], 0, ',', '.'); ?>
                </div>
            <?php endif; ?>
            <?php if ($penjualan['pajak'] > 0): ?>
                <div style="text-align: right; margin: 5px 0;">
                    <strong>Pajak:</strong> + Rp <?php echo number_format($penjualan['pajak'], 0, ',', '.'); ?>
                </div>
            <?php endif; ?>

            <div style="border: 2px solid #000; padding: 8px; margin: 10px 0;">
                <div style="text-align: center; font-size: 16px; font-weight: bold;">
                    Rp <?php echo number_format($penjualan['total_harga'], 0, ',', '.'); ?>
                </div>
            </div>

            <div style="text-align: right; margin: 5px 0;">
                <strong>Bayar:</strong> Rp <?php echo number_format($penjualan['jumlah_bayar'], 0, ',', '.'); ?>
            </div>
            <div style="text-align: right; margin: 5px 0;">
                <strong>Kembalian:</strong> Rp <?php echo number_format($penjualan['kembalian'], 0, ',', '.'); ?>
            </div>
        </div>

        <div class="footer">
            <p><?php echo $penjualan['metode_bayar']; ?></p>
            <p style="margin: 10px 0;">Terima kasih atas pembelian Anda!</p>
            <small><?php echo date('d/m/Y H:i:s'); ?></small>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>

</html>