<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Auth::index');

$routes->group('home', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Home::index');
});

$routes->group('auth', function($routes) {
    $routes->get('/', 'Auth::index');
    $routes->post('login', 'Auth::login');
    $routes->get('refresh-captcha', 'Auth::refreshCaptcha');
    $routes->get('device-conf', 'Auth::getDeviceConf');
    $routes->post('device-logs', 'DeviceLog::store');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('dashboard', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Dashboard::index');
});

$routes->group('siswa', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Siswa\Siswa::index');
    $routes->get('getData', 'Siswa\Siswa::getData');
    $routes->post('add', 'Siswa\Siswa::create');
    $routes->post('update/(:num)', 'Siswa\Siswa::update/$1');
    $routes->get('delete/(:num)', 'Siswa\Siswa::delete/$1');

    $routes->get('import', 'Siswa\Siswa::import');
    $routes->post('upload', 'Siswa\SiswaImport::upload');
    $routes->post('saveFile', 'Siswa\SiswaImport::save');
});

$routes->group('prestasi', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Siswa\Prestasi::index');
    $routes->get('getData', 'Siswa\Prestasi::getData');
    $routes->post('add', 'Siswa\Prestasi::create');
    $routes->post('update/(:num)', 'Siswa\Prestasi::update/$1');
    $routes->get('delete/(:num)', 'Siswa\Prestasi::delete/$1');
    $routes->get('getDataSiswa', 'Siswa\Prestasi::getDataSiswa');
    $routes->get('getDataKelas', 'Siswa\Prestasi::getDataKelas');
});


$routes->group('pengumuman', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Pengumuman\Pengumuman::index');
    //$routes->get('getKategori', 'Pengumuman\Pengumuman::getKategori');
    $routes->get('getData', 'Pengumuman\Pengumuman::getData');
    $routes->post('add', 'Pengumuman\Pengumuman::create');
    $routes->post('update/(:num)', 'Pengumuman\Pengumuman::update/$1');
    $routes->get('delete/(:num)', 'Pengumuman\Pengumuman::delete/$1');

});

$routes->group('agenda', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Agenda\Agenda::index');
    $routes->get('getKategori', 'Agenda\Agenda::getKategori');
    $routes->get('getData', 'Agenda\Agenda::getData');
    $routes->post('add', 'Agenda\Agenda::create');
    $routes->post('update/(:num)', 'Agenda\Agenda::update/$1');
    $routes->get('delete/(:num)', 'Agenda\Agenda::delete/$1');
    

});

$routes->group('nilai', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Nilai::index');
});

$routes->group('absensi', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Siswa\Absensi::index');
    $routes->get('getData', 'Siswa\Absensi::getData');
    $routes->post('add', 'Siswa\Absensi::create');
    $routes->post('update/(:num)', 'Siswa\Absensi::update/$1');
    $routes->get('delete/(:num)', 'Siswa\Absensi::delete/$1');
    
});

$routes->group('pelanggaran', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Siswa\Pelanggaran::index');
    $routes->get('getData', 'Siswa\Pelanggaran::getData');
    $routes->post('add', 'Siswa\Pelanggaran::create');
    $routes->post('update/(:num)', 'Siswa\Pelanggaran::update/$1');
    $routes->get('delete/(:num)', 'Siswa\Pelanggaran::delete/$1');
});

$routes->group('guru', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Guru::index');
});

$routes->group('mapel', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('/', 'Mapel::index');
});


$routes->group('master', ['filter' => 'adminfilter'], function($routes) {
    $routes->get('guru', 'Master\Master::guru');
    $routes->get('kelas', 'Master\Master::kelas');
    $routes->get('jenis-agenda', 'Master\Master::agenda');
    $routes->get('mapel', 'Master\Master::mapel');
    $routes->get('kategori-informasi', 'Master\Master::kategoriInformasi');
    $routes->get('kategori-prestasi', 'Master\Master::kategoriPrestasi');
    $routes->get('jenis-pelanggaran', 'Master\Master::jenisPelanggaran');
    $routes->get('sub-pelanggaran', 'Master\Master::subPelanggaran');
    $routes->get('tingkat-kasus', 'Master\Master::tingkatKasus');
    $routes->get('alasan-terlambat', 'Master\Master::alasanTerlambat');
    $routes->get('sanksi', 'Master\Master::sanksi');
    $routes->get('jenjang', 'Master\Master::jenjang');
    $routes->get('profil-sekolah', 'Master\Master::profilSekolah');
    $routes->get('pengaturan', 'Master\Master::pengaturan');
});

/*
    * --------------------------------------------------------------------
    * Additional Routing
    * --------------------------------------------------------------------
    *
    * routing ke controller master / controller kelas, guru, etc.
    */

$routes->group('mst', ['filter' => 'adminfilter'], function($routes) {
    // Routes for Kelas
    $routes->get('getDataKelas', 'Master\Kelas::index');
    $routes->post('inputKelas', 'Master\Kelas::create');
    $routes->post('updateKelas/(:num)', 'Master\Kelas::update/$1');
    $routes->get('deleteKelas/(:num)', 'Master\Kelas::delete/$1');
    
    // Routes for Guru
    $routes->get('getDataGuru', 'Master\Guru::index');
    $routes->post('inputGuru', 'Master\Guru::create');
    $routes->post('updateGuru/(:num)', 'Master\Guru::update/$1');
    $routes->get('deleteGuru/(:num)', 'Master\Guru::delete/$1');
    $routes->get('getDataGuruById/(:num)', 'Master\Guru::getDataGuruById/$1');
    $routes->get('getDataGuruByNIP/(:any)', 'Master\Guru::getDataGuruByNIP/$1');
    
    // Routes for Mapel
    $routes->get('getDataMapel', 'Master\Mapel::index');
    $routes->post('inputMapel', 'Master\Mapel::create');
    $routes->post('updateMapel/(:num)', 'Master\Mapel::update/$1');
    $routes->get('deleteMapel/(:num)', 'Master\Mapel::delete/$1');

    // Routes for Jenis Pelanggaran
    $routes->get('getDataJenisPelanggaran', 'Master\JenisPelanggaran::index');
    $routes->post('inputJenisPelanggaran', 'Master\JenisPelanggaran::create');
    $routes->post('updateJenisPelanggaran/(:num)', 'Master\JenisPelanggaran::update/$1');
    $routes->get('deleteJenisPelanggaran/(:num)', 'Master\JenisPelanggaran::delete/$1');

    // Routes for Sub Pelanggaran
    $routes->get('getDataSubPelanggaran', 'Master\SubPelanggaran::index');
    $routes->post('inputSubPelanggaran', 'Master\SubPelanggaran::create');
    $routes->post('updateSubPelanggaran/(:num)', 'Master\SubPelanggaran::update/$1');
    $routes->get('deleteSubPelanggaran/(:num)', 'Master\SubPelanggaran::delete/$1');    

    // Routes for Tingkat Kasus
    $routes->get('getDataTingkatKasus', 'Master\TingkatKasus::index');
    $routes->post('inputTingkatKasus', 'Master\TingkatKasus::create');
    $routes->post('updateTingkatKasus/(:num)', 'Master\TingkatKasus::update/$1');
    $routes->get('deleteTingkatKasus/(:num)', 'Master\TingkatKasus::delete/$1');

    // Routes for Kategori Informasi
    $routes->get('getDataKategoriInformasi', 'Master\KategoriInformasi::index');
    $routes->post('inputKategoriInformasi', 'Master\KategoriInformasi::create');
    $routes->post('updateKategoriInformasi/(:num)', 'Master\KategoriInformasi::update/$1');
    $routes->get('deleteKategoriInformasi/(:num)', 'Master\KategoriInformasi::delete/$1');

    // Routes for Jenis Agenda
    $routes->get('getDataJenisAgenda', 'Master\JenisAgenda::index');
    $routes->post('inputJenisAgenda', 'Master\JenisAgenda::create');
    $routes->post('updateJenisAgenda/(:num)', 'Master\JenisAgenda::update/$1');
    $routes->get('deleteJenisAgenda/(:num)', 'Master\JenisAgenda::delete/$1');

    // Routes for Alasan Terlambat
    $routes->get('getDataAlasanTerlambat', 'Master\AlasanTerlambat::index');
    $routes->post('inputAlasanTerlambat', 'Master\AlasanTerlambat::create');
    $routes->post('updateAlasanTerlambat/(:num)', 'Master\AlasanTerlambat   ::update/$1');
    $routes->get('deleteAlasanTerlambat/(:num)', 'Master\AlasanTerlambat::delete/$1');

    // Routes for Sanksi
    $routes->get('getDataSanksi', 'Master\Sanksi::index');    
    $routes->post('inputSanksi', 'Master\Sanksi::create');
    $routes->post('updateSanksi/(:num)', 'Master\Sanksi::update/$1');
    $routes->get('deleteSanksi/(:num)', 'Master\Sanksi::delete/$1');

    // Routes for Jenjang
    $routes->get('getDataJenjang', 'Master\Jenjang::index');    
    $routes->post('inputJenjang', 'Master\Jenjang::create');
    $routes->post('updateJenjang/(:num)', 'Master\Jenjang::update/$1');
    $routes->get('deleteJenjang/(:num)', 'Master\Jenjang::delete/$1');

    // Routes for Profil Sekolah
    $routes->get('getDataProfilSekolah', 'Master\ProfilSekolah::index');    
    $routes->post('inputProfilSekolah', 'Master\ProfilSekolah::create');
    $routes->post('updateProfilSekolah/(:num)', 'Master\ProfilSekolah::update/$1');
    $routes->get('deleteProfilSekolah/(:num)', 'Master\ProfilSekolah::delete/$1');

    // Routes for Pengaturan
    $routes->get('getDataPengaturan', 'Master\Pengaturan::index');    
    $routes->post('inputPengaturan', 'Master\Pengaturan::create');
    $routes->post('updatePengaturan/(:num)', 'Master\Pengaturan::update/$1');
    $routes->get('deletePengaturan/(:num)', 'Master\Pengaturan::delete/$1');

});
