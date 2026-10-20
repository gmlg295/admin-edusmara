<?php

namespace App\Modules\SMS\Controllers\Master;
use App\Controllers\BaseController;

class Master extends BaseController
{
    public function kelas(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_kelas', [
            'title' => 'Dashboard Admin - EDUSMARA',
            'active' => 'dashboard',
            'load_js' => 'page/master/kelas.js',
            'load_css' => 'page/master/kelas-style.css',
            'enable_menu' => false,
        ]);
    }
    
    public function guru(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_guru', [
            'title' => 'Master Guru - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/guru.js',
            'load_css' => 'page/master/guru-style.css',
            'enable_menu' => false,
        ]);
    }
    
    public function agenda(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_agenda', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/agenda.js',
            'load_css' => 'page/master/agenda-style.css',
            'enable_menu' => false,
        ]);
    }
    
    public function mapel(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function kategoriInformasi(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function kategoriPrestasi(): string
    {
        return view('content/master/master_kategoriprestasi', [
            'title' => 'Kategori Prestasi - EDUSMARA',
            'active' => 'kategoriprestasi',
            'load_js' => 'page/master/kategoriprestasi.js',
            'load_css' => 'page/master/kategoriprestasi-style.css',
            'enable_menu' => false,
        ]);
    }

    public function jenisAgenda(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function jenisPelanggaran(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function subPelanggaran(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }


    public function tingkatKasus(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_tingkatkasus', [
            'title' => 'Tingkat Kasus - EDUSMARA',
            'active' => 'tingkatkasus',
            'load_js' => 'page/master/tingkatkasus.js',
            'load_css' => 'page/master/tingkatkasus-style.css',
            'enable_menu' => false,
        ]);
    }

    public function alasanTerlambat(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function sanksi(): string
    {
        return view('content/master/master_sanksi', [
            'title' => 'Sanksi - EDUSMARA',
            'active' => 'sanksi',
            'load_js' => 'page/master/sanksi.js',
            'load_css' => 'page/master/sanksi-style.css',
            'enable_menu' => false,
        ]);
    }

    public function jenjang(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function profilSekolah(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function pengaturan(): string
    {
        return view('App\Modules\SMS\Views\content\master\master_pengumuman', [
            'title' => 'Master Agenda - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/master/pengumuman.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }
}
