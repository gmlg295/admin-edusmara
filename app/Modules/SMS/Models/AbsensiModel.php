<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table            = 'mstsiswa';
    protected $primaryKey       = 'id_siswa';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields = false;
    //protected $allowedFields    = [];
    
    // [
    //     'nip', 'nuptk', 'nama_lengkap', 'nama_tanpa_gelar', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'no_hp', 'email', 'pendidikan_terakhir', 'tahun_lulus', 'status_kepegawaian', 'jabatan_fungsional', 'tugas_tambahan', 'foto', 'is_aktif', 'created_by', 'created_at', 'updated_by', 'updated_at'
    // ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4 [[7]]
    protected $validationRules = [
        'nama_lengkap'          => 'required|min_length[2]|max_length[20]',
        'jenis_kelamin'         => 'required|min_length[1]|in_list[L,P]',
        'tempat_lahir'          => 'required|min_length[2]|max_length[40]',
        'tanggal_lahir'         => 'required|valid_date[Y-m-d]',
        'alamat_lengkap'        => 'required|min_length[2]|max_length[50]',
        'jabatan_struktural'     => 'required|min_length[2]|max_length[50]',
        'status_kepegawaian'     => 'required|min_length[2]|max_length[10]',
        'golongan_pangkat'       => 'required|min_length[2]|max_length[50]',
        'status_pegawai'           => 'required|min_length[2]|max_length[10]',
        'universitas'           => 'required|min_length[2]|max_length[50]',
        'jurusan'               => 'required|min_length[2]|max_length[50]',
        'jenjang_pendidikan'       => 'required|min_length[2]|max_length[3]',
        'tahun_lulus'           => 'required|integer|greater_than[1990]',
        'sertifikasi_guru'       => 'required|min_length[1]|in_list[1,0]',
        'nip'                   => 'permit_empty|max_length[20]',
        'nuptk'                 => 'permit_empty|max_length[20]',
        //'role'                  => 'permit_empty|in_list[admin,kepsek,wakasek,guru,bk,tu,pustakawan,laboran]',
        'nomor_hp'              => 'permit_empty|max_length[16]',
    ];

    protected $validationMessages = [
        'nama_kelas' => [
            'required'   => 'Nama kelas wajib diisi.',
            'min_length' => 'Nama kelas minimal 2 karakter.',
        ],
        'tingkat' => [
            'required' => 'Tingkat wajib diisi.',
            'in_list'  => 'Tingkat hanya boleh 10, 11, atau 12.',
        ],
        'tahun_pelajaran' => [
            'required' => 'Tahun pelajaran wajib diisi.',
        ],
        'kapasitas' => [
            'required'      => 'Kapasitas wajib diisi.',
            'greater_than'  => 'Kapasitas harus lebih dari 0.',
        ],
    ];

    public function getAllData()
    {
        $siswa = $this->db->query("SELECT id_siswa, nisn,nis, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, agama, 
        nomor_hp, email, alamat_lengkap, nama_ibu, nama_ayah, pekerjaan_ayah, pekerjaan_ibu, no_hp_wali, hubungan_wali, tanggal_masuk, status_siswa,diterima_dari_kelas,asal_sekolah, mstkelas.`tingkat` as kelas, alamat_wali,
        mstkelas.`nama_kelas`, mstkelas.`ruang_kelas`
        FROM mstsiswa INNER JOIN mstkelas ON mstkelas.`id_kelas`=mstsiswa.`id_kelas` WHERE mstsiswa.`status_siswa` = 'aktif' ORDER BY mstsiswa.id_kelas ASC, mstsiswa.nama_lengkap ASC;")->getResult();
        $array = [];
        foreach ($siswa as $key => $value) {
           $array[] =[
            'id' =>(int) $value->id_siswa,
            'nisn' => $value->nisn,
            'nis' => $value->nis,
            'nama_lengkap' => $value->nama_lengkap,
            'jenis_kelamin' => $value->jenis_kelamin,
            'tempat_lahir' => $value->tempat_lahir,
            'tanggal_lahir' => $value->tanggal_lahir,
            'agama' => $value->agama,
            'nomor_hp' => $value->nomor_hp,
            'email' => $value->email,
            'alamat_lengkap' => $value->alamat_lengkap,
            'tanggal_masuk' => $value->tanggal_masuk,
            'diterima_dari_kelas' => $value->diterima_dari_kelas,
            'asal_sekolah' => $value->asal_sekolah,
            'status' => $value->status_siswa,
            'nama_kelas' => $value->nama_kelas,
            'ruang_kelas' => $value->ruang_kelas,
            'kelas' =>(int) $value->kelas,
            'wali'=>[
                'nama'=>$value->nama_ayah ? $value->nama_ayah : $value->nama_ibu,
                'pekerjaan'=>$value->pekerjaan_ayah ? $value->pekerjaan_ayah : $value->pekerjaan_ibu,
                'nomor_hp'=>$value->no_hp_wali,
                'hubungan'=>$value->hubungan_wali,
                'alamat'=>$value->alamat_wali

            ]


           ];


        }
        return $array;

    }
}