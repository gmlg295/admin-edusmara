<?php

namespace App\Models;

use CodeIgniter\Model;

class PrestasiSiswaModel extends Model
{
    protected $table            = 'mstguru';
    protected $primaryKey       = 'id_guru';
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
        return $this->db->query("SELECT *, (SELECT mstguru.nama_lengkap FROM mstguru WHERE mstguru.id_guru = mstkelas.wali_kelas_id) as nama_wali FROM `mstkelas` ORDER BY mstkelas.tingkat ASC, mstkelas.urutan ASC;")->getResult();
    }
}