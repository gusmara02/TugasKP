<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('format_indo')) {
    function format_indo($date)
    {
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun;
        return ($result);
    }
}

if (!function_exists('format_indo_datetime')) {
    function format_indo_datetime($date)
    {
        $BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $tahun = substr($date, 0, 4);
        $bulan = substr($date, 5, 2);
        $tgl   = substr($date, 8, 2);
        $result = $tgl . " " . $BulanIndo[(int)$bulan - 1] . " " . $tahun . " " . substr($date, 10, 9);
        return ($result);
    }
}

if (!function_exists('isHoliday')) {
    function getHoliday($dateString)
    {
        $holidays = json_decode(file_get_contents("https://api-harilibur.vercel.app/api"));
        $result = null;

        foreach ($holidays as $holiday) {
            if ($holiday->is_national_holiday && $dateString === $holiday->holiday_date) {
                $result = $holiday->holiday_name;
                return $result;
            }
        }

        // Untuk mengecek apakah hari itu sabtu atau minggu
        if (!$result) {
            $day = (int)date("w", strtotime($dateString));
            $result = $day === 0 || $day === 6 ? "Weekend" : null;
        }

        return $result;
    }
}
