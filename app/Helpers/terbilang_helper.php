<?php
function terbilang($number)
{
    $terbilang = '';
    if ($number < 0) {
        $terbilang = 'minus ' . trim(penyebut($number));
    } else {
        $terbilang = trim(penyebut($number));
    }
    // Ubah string menjadi uppercase
    return strtoupper($terbilang);
}


/**
 * Converts a number to Indonesian words representation
 *
 * @param float $number The number to convert
 * @return string The number in words
 */

function penyebut($number)
{
    $number = abs($number);
    $words = array('', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas');

    if ($number < 12) {
        return $words[$number];
    } elseif ($number < 20) {
        return terbilang($number - 10) . ' belas';
    } elseif ($number < 100) {
        return terbilang(floor($number / 10)) . ' puluh ' . terbilang($number % 10);
    } elseif ($number < 200) {
        return 'seratus ' . terbilang($number - 100);
    } elseif ($number < 1000) {
        return terbilang(floor($number / 100)) . ' ratus ' . terbilang($number % 100);
    } elseif ($number < 2000) {
        return 'seribu ' . terbilang($number - 1000);
    } elseif ($number < 1000000) {
        return terbilang(floor($number / 1000)) . ' ribu ' . terbilang($number % 1000);
    } elseif ($number < 1000000000) {
        return terbilang(floor($number / 1000000)) . ' juta ' . terbilang($number % 1000000);
    } elseif ($number < 1000000000000) {
        return terbilang(floor($number / 1000000000)) . ' milyar ' . terbilang($number % 1000000000);
    } elseif ($number < 1000000000000000) {
        return terbilang(floor($number / 1000000000000)) . ' trilyun ' . terbilang($number % 1000000000000);
    }

    return '';
}
