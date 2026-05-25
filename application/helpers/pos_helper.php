<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * POS Helper Functions
 * Fungsi-fungsi utility untuk sistem POS
 */

// ========================================
// FORMAT FUNCTIONS
// ========================================

/**
 * Format angka ke Rupiah
 * 
 * @param float $angka
 * @param int $decimals
 * @return string
 */
if (!function_exists('format_rupiah')) {
    function format_rupiah($angka, $decimals = 0)
    {
        return 'Rp ' . number_format($angka, $decimals, ',', '.');
    }
}

/**
 * Format tanggal Indonesia
 * 
 * @param string $date
 * @param string $format
 * @return string
 */
if (!function_exists('format_tanggal')) {
    function format_tanggal($date, $format = 'd M Y')
    {
        $bulan = array(
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );

        $pecah = explode('-', $date);
        return $pecah[2] . ' ' . $bulan[(int)$pecah[1]] . ' ' . $pecah[0];
    }
}

/**
 * Format datetime Indonesia
 * 
 * @param string $datetime
 * @return string
 */
if (!function_exists('format_datetime')) {
    function format_datetime($datetime)
    {
        return date('d/m/Y H:i:s', strtotime($datetime));
    }
}

// ========================================
// GENERATE FUNCTIONS
// ========================================

/**
 * Generate kode produk otomatis
 * Format: PROD-20260525-0001
 * 
 * @param string $kategori
 * @return string
 */
if (!function_exists('generate_kode_produk')) {
    function generate_kode_produk($kategori = 'PROD')
    {
        $date = date('Ymd');
        $random = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return $kategori . '-' . $date . '-' . $random;
    }
}

/**
 * Generate nomor transaksi otomatis
 * Format: TRX20260525-0001
 * 
 * @return string
 */
if (!function_exists('generate_no_transaksi')) {
    function generate_no_transaksi()
    {
        $date = date('Ymd');
        $seq = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return 'TRX' . $date . '-' . $seq;
    }
}

/**
 * Generate nomor invoice
 * Format: INV/2026/05/0001
 * 
 * @return string
 */
if (!function_exists('generate_invoice')) {
    function generate_invoice()
    {
        $date = date('Y/m');
        $seq = str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return 'INV/' . $date . '/' . $seq;
    }
}

// ========================================
// VALIDATION FUNCTIONS
// ========================================

/**
 * Validasi input stok
 * 
 * @param int $stok
 * @return bool
 */
if (!function_exists('validate_stok')) {
    function validate_stok($stok)
    {
        return is_numeric($stok) && $stok >= 0;
    }
}

/**
 * Validasi harga
 * 
 * @param float $harga
 * @return bool
 */
if (!function_exists('validate_harga')) {
    function validate_harga($harga)
    {
        return is_numeric($harga) && $harga > 0;
    }
}

/**
 * Validasi qty penjualan
 * 
 * @param int $qty
 * @param int $stok_tersedia
 * @return array
 */
if (!function_exists('validate_qty')) {
    function validate_qty($qty, $stok_tersedia)
    {
        $response = [
            'valid' => false,
            'message' => 'Qty tidak valid'
        ];

        if (!is_numeric($qty) || $qty <= 0) {
            $response['message'] = 'Qty harus lebih dari 0';
            return $response;
        }

        if ($qty > $stok_tersedia) {
            $response['message'] = 'Stok tidak cukup. Tersedia: ' . $stok_tersedia;
            return $response;
        }

        $response['valid'] = true;
        return $response;
    }
}

// ========================================
// CALCULATION FUNCTIONS
// ========================================

/**
 * Hitung margin keuntungan produk
 * 
 * @param float $harga_beli
 * @param float $harga_jual
 * @return float
 */
if (!function_exists('hitung_margin')) {
    function hitung_margin($harga_beli, $harga_jual)
    {
        if ($harga_beli == 0) return 0;
        return (($harga_jual - $harga_beli) / $harga_beli) * 100;
    }
}

/**
 * Hitung total dengan pajak
 * 
 * @param float $subtotal
 * @param float $pajak_persen
 * @param float $diskon
 * @return float
 */
if (!function_exists('hitung_total')) {
    function hitung_total($subtotal, $pajak_persen = 0, $diskon = 0)
    {
        $pajak = ($subtotal * $pajak_persen) / 100;
        return ($subtotal - $diskon) + $pajak;
    }
}

/**
 * Hitung kembalian
 * 
 * @param float $total
 * @param float $bayar
 * @return float
 */
if (!function_exists('hitung_kembalian')) {
    function hitung_kembalian($total, $bayar)
    {
        return $bayar - $total;
    }
}

/**
 * Hitung diskon dari persen
 * 
 * @param float $total
 * @param float $diskon_persen
 * @return float
 */
if (!function_exists('hitung_diskon')) {
    function hitung_diskon($total, $diskon_persen)
    {
        return ($total * $diskon_persen) / 100;
    }
}

// ========================================
// ARRAY FUNCTIONS
// ========================================

/**
 * Cari di array by column
 * 
 * @param array $array
 * @param string $column
 * @param mixed $value
 * @return mixed
 */
if (!function_exists('array_search_column')) {
    function array_search_column($array, $column, $value)
    {
        foreach ($array as $item) {
            if (isset($item[$column]) && $item[$column] == $value) {
                return $item;
            }
        }
        return null;
    }
}

/**
 * Extract column dari array of arrays
 * 
 * @param array $array
 * @param string $column
 * @return array
 */
if (!function_exists('array_extract_column')) {
    function array_extract_column($array, $column)
    {
        $result = array();
        foreach ($array as $item) {
            if (isset($item[$column])) {
                $result[] = $item[$column];
            }
        }
        return $result;
    }
}

// ========================================
// STATUS & BADGE FUNCTIONS
// ========================================

/**
 * Get badge HTML for status transaksi
 * 
 * @param string $status
 * @return string
 */
if (!function_exists('badge_status_transaksi')) {
    function badge_status_transaksi($status)
    {
        $badges = array(
            'selesai' => '<span class="badge badge-success">Selesai</span>',
            'pending' => '<span class="badge badge-warning">Pending</span>',
            'batal' => '<span class="badge badge-danger">Batal</span>'
        );

        return isset($badges[$status]) ? $badges[$status] : '<span class="badge badge-secondary">Unknown</span>';
    }
}

/**
 * Get badge HTML for metode pembayaran
 * 
 * @param string $metode
 * @return string
 */
if (!function_exists('badge_metode_bayar')) {
    function badge_metode_bayar($metode)
    {
        $badges = array(
            'tunai' => '<span class="badge badge-info">Tunai</span>',
            'kartu_kredit' => '<span class="badge badge-primary">Kartu Kredit</span>',
            'transfer' => '<span class="badge badge-secondary">Transfer</span>',
            'cek' => '<span class="badge badge-warning">Cek</span>',
            'lainnya' => '<span class="badge badge-dark">Lainnya</span>'
        );

        return isset($badges[$metode]) ? $badges[$metode] : '<span class="badge badge-secondary">Unknown</span>';
    }
}

// ========================================
// LOG & AUDIT FUNCTIONS
// ========================================

/**
 * Log aktivitas user
 * 
 * @param string $action
 * @param string $modul
 * @param string $deskripsi
 * @param int $user_id
 * @return void
 */
if (!function_exists('log_aktivitas')) {
    function log_aktivitas($action, $modul, $deskripsi, $user_id = null)
    {
        $CI = &get_instance();

        $log_data = array(
            'user_id' => $user_id,
            'action' => $action,
            'modul' => $modul,
            'deskripsi' => $deskripsi,
            'ip_address' => $CI->input->ip_address(),
            'user_agent' => $CI->input->user_agent(),
            'timestamp' => date('Y-m-d H:i:s')
        );

        // TODO: Save to audit_log table if exists
        // $CI->db->insert('audit_log', $log_data);
    }
}

// ========================================
// EXPORT FUNCTIONS
// ========================================

/**
 * Export array to CSV
 * 
 * @param array $data
 * @param string $filename
 * @return void
 */
if (!function_exists('export_csv')) {
    function export_csv($data, $filename = 'export')
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');

        if (!empty($data)) {
            // Header
            fputcsv($output, array_keys($data[0]));

            // Data rows
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
        }

        fclose($output);
        exit;
    }
}

/**
 * Generate PDF filename
 * 
 * @param string $prefix
 * @return string
 */
if (!function_exists('generate_pdf_name')) {
    function generate_pdf_name($prefix = 'doc')
    {
        return $prefix . '_' . date('Y-m-d_H-i-s') . '.pdf';
    }
}
