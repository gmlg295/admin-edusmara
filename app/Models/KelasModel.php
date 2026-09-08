<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table            = 'mstkelas';
    protected $primaryKey       = 'id_kelas';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nama_kelas', 'tingkat', 'tahun_pelajaran', 'kapasitas',
        'wali_kelas_id', 'wali_kelas', 'jurusan', 'kurikulum',
        'ruang_kelas', 'is_aktif', 'urutan', 'created_by'
    ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4 [[7]]
    protected $validationRules = [
        'nama_kelas'      => 'required|min_length[2]|max_length[20]',
        'tingkat'         => 'required|integer|in_list[10,11,12]',
        'tahun_pelajaran' => 'required|max_length[9]',
        'ruang_kelas'     => 'required|max_length[50]',
        'kapasitas'       => 'required|integer|greater_than[0]',
        'jurusan'         => 'permit_empty|max_length[50]',
        'kurikulum'       => 'permit_empty|max_length[50]',
        'is_aktif'        => 'permit_empty|in_list[0,1]',
        'urutan'          => 'permit_empty|integer',
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
    
    public function getGuruWaliKelas()
    {
        return $this->db->query("SELECT *  FROM mstguru WHERE mstguru.role='guru' ORDER BY mstguru.nama_lengkap ASC")->getResult();
    }
}