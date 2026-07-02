<?php
// Date Helper (Indonesian Format)

/**
 * Get Indonesian Day Name from English name or Date
 */
function get_indo_day($date) {
    $day_eng = date('D', strtotime($date));
    $days = [
        'Sun' => 'Minggu',
        'Mon' => 'Senin',
        'Tue' => 'Selasa',
        'Wed' => 'Rabu',
        'Thu' => 'Kamis',
        'Fri' => 'Jumat',
        'Sat' => 'Sabtu'
    ];
    return $days[$day_eng] ?? $day_eng;
}

/**
 * Get Indonesian Month Name from Month Number or Date
 */
function get_indo_month($month_num) {
    if (is_string($month_num) && !is_numeric($month_num)) {
        $month_num = date('n', strtotime($month_num));
    }
    
    $months = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
    return $months[(int)$month_num] ?? $month_num;
}

/**
 * Format Date to Indonesian format: d F Y (e.g. 17 Agustus 1945)
 */
function format_indo_date($date) {
    if (empty($date)) return '-';
    $d = date('d', strtotime($date));
    $m = get_indo_month(date('n', strtotime($date)));
    $y = date('Y', strtotime($date));
    return "$d $m $y";
}

/**
 * List all months in Indonesian
 */
function get_months_list() {
    return [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember'
    ];
}
