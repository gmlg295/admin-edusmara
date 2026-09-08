<?php

use App\Models\Mymodel as Dapur;

function getDataMenus($id){
	$mdl = new  Dapur(['table'=>'menu', 'pk'=>'id_menu']);
	return $mdl->tertentu(['parent_id'=>$id])->getResult();
}

function genPass($pass, $salt = '90FsBnABCNDPOWER')
{
	return password_hash($pass . $salt, PASSWORD_DEFAULT);
}

function decPass($pass, $hash, $salt = '90FsBnABCNDPOWER')
{
	return password_verify($pass . $salt, $hash);
}

function waktu()
{
	date_default_timezone_set('Asia/Jakarta');
	return date("Y-m-d H:i:s");
}

function tanggal()
{
	date_default_timezone_set('Asia/Jakarta');
	return date("Y-m-d");
}

function tgl_indo($tanggal)
{
	$tl_new = substr($tanggal, 0, 10);
	$bulan = array(
		1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
		0 => '--',
	);
	$split = explode('-', $tl_new);
	if (!empty($split[2])) {
		return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
	} else {
		return "--";
	}
}
function rp($x)
{
	return number_format($x, 0, ",", ".");
}

function tgl_indojam($tgl, $pemisah)
{
	return substr($tgl, 11, 8) . ' ' . $pemisah . ' ' . substr($tgl, 8, 2) . ' ' . bulan(substr($tgl, 5, 2)) . ' ' . substr($tgl, 0, 4);
}

function SelisihWaktu($tgl)
{
	date_default_timezone_set('Asia/Jakarta');
	$awal  = new DateTime($tgl);
	$akhir = new DateTime(); // Waktu sekarang
	$diff  = $awal->diff($akhir);
	$text  = "";

	if ($diff->s < 60) {
		$text = $diff->s . ' detik yang lalu';
	} elseif ($diff->s > 60) {
		$text = $diff->i . ' menit yang lalu';
	} elseif ($diff->i > 60) {
		$text = $diff->h . ' jam yang lalu';
	} elseif ($diff->h > 24) {
		$text = $diff->d . ' hari yang lalu';
	} elseif ($diff->d > 30) {
		$text = $diff->m . ' bulan yang lalu';
	} elseif ($diff->m > 11) {
		$text = $diff->y . ' tahun yang lalu';
	}
	return $text;
}
function penyebut($nilai)
{
	$nilai = abs($nilai);
	$huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
	$temp = "";
	if ($nilai < 12) {
		$temp = " " . $huruf[$nilai];
	} else if ($nilai < 20) {
		$temp = penyebut($nilai - 10) . " belas";
	} else if ($nilai < 100) {
		$temp = penyebut($nilai / 10) . " puluh" . penyebut($nilai % 10);
	} else if ($nilai < 200) {
		$temp = " seratus" . penyebut($nilai - 100);
	} else if ($nilai < 1000) {
		$temp = penyebut($nilai / 100) . " ratus" . penyebut($nilai % 100);
	} else if ($nilai < 2000) {
		$temp = " seribu" . penyebut($nilai - 1000);
	} else if ($nilai < 1000000) {
		$temp = penyebut($nilai / 1000) . " ribu" . penyebut($nilai % 1000);
	} else if ($nilai < 1000000000) {
		$temp = penyebut($nilai / 1000000) . " juta" . penyebut($nilai % 1000000);
	} else if ($nilai < 1000000000000) {
		$temp = penyebut($nilai / 1000000000) . " milyar" . penyebut(fmod($nilai, 1000000000));
	} else if ($nilai < 1000000000000000) {
		$temp = penyebut($nilai / 1000000000000) . " trilyun" . penyebut(fmod($nilai, 1000000000000));
	}
	return $temp;
}

function terbilang($nilai)
{
	if ($nilai < 0) {
		$hasil = "minus " . trim(penyebut($nilai));
	} else {
		$hasil = trim(penyebut($nilai));
	}
	return $hasil;
}

function encrypt_url($string)
{
	$output = false;
	$security       = parse_ini_file("security.ini");
	$secret_key     = $security["encryption_key"];
	$secret_iv      = $security["iv"];
	$encrypt_method = $security["encryption_mechanism"];
	$key    = hash("sha256", $secret_key);
	$iv     = substr(hash("sha256", $secret_iv), 0, 16);
	$result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
	return str_replace("=", "", $result);
}

function decrypt_url($string)
{
	$output = false;
	$security       = parse_ini_file("security.ini");
	$secret_key     = $security["encryption_key"];
	$secret_iv      = $security["iv"];
	$encrypt_method = $security["encryption_mechanism"];
	$key    = hash("sha256", $secret_key);
	$iv = substr(hash("sha256", $secret_iv), 0, 16);
	$output = openssl_decrypt($string, $encrypt_method, $key, 0, $iv);
	return $output;
}

function bulan($val)
{
	switch ($val) {
		case 1:
			return 'Januari';
			break;
		case 2:
			return 'Februari';
			break;
		case 3:
			return 'Maret';
			break;
		case 4:
			return 'April';
			break;
		case 5:
			return 'Mei';
			break;
		case 6:
			return 'Juni';
			break;
		case 7:
			return 'Juli';
			break;
		case 8:
			return 'Agustus';
			break;
		case 9:
			return 'September';
			break;
		case 10:
			return 'Oktober';
			break;
		case 11:
			return 'November';
			break;
		case 12:
			return 'Desember';
			break;
	}
}


function randomStrings($length_of_string)
{
	$str_result = '0123456789abcdefghijklmnopqrstuvwxyz';
	return substr(str_shuffle($str_result), 0, $length_of_string);
}

function namaHari($hari)
{
	if ($hari == 'Sunday') {
		return 'Minggu';
	} elseif ($hari == 'Monday') {
		return 'Senin';
	} elseif ($hari == 'Tuesday') {
		return 'Selasa';
	} elseif ($hari == 'Wednesday') {
		return 'Rabu';
	} elseif ($hari == 'Thursday') {
		return 'Kamis';
	} elseif ($hari == 'Friday') {
		return 'Jumat';
	} elseif ($hari == 'Saturday') {
		return 'Sabtu';
	}
}
