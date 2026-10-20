<?php

namespace App\Modules\SMS\Models;

use CodeIgniter\Model;

class TingkatKasusModel extends Model
{
    protected $table            = 'reftingkatkasus';
    protected $primaryKey       = 'id_tingkat_kasus';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nama_tingkat', 'kode_tingkat', 'bobot_poin', 'urutan', 'is_aktif'
    ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4
    protected $validationRules = [
        'nama_tingkat'       => 'required|min_length[2]|max_length[100]',
        'kode_tingkat'       => 'required|min_length[1]|max_length[20]',
        'bobot_poin'         => 'required|numeric|greater_than_equal_to[0]',
        'urutan'             => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'nama_tingkat' => [
            'required'   => 'Nama tingkat kasus wajib diisi.',
            'min_length' => 'Nama tingkat kasus minimal 2 karakter.',
            'max_length' => 'Nama tingkat kasus maksimal 100 karakter.',
        ],
        'kode_tingkat' => [
            'required'   => 'Kode tingkat kasus wajib diisi.',
            'min_length' => 'Kode tingkat kasus minimal 1 karakter.',
            'max_length' => 'Kode tingkat kasus maksimal 20 karakter.',
            'is_unique'  => 'Kode tingkat kasus sudah digunakan.',
        ],
        'bobot_poin' => [
            'required'          => 'Bobot poin wajib diisi.',
            'numeric'           => 'Bobot poin harus berupa angka.',
            'greater_than_equal_to' => 'Bobot poin tidak boleh negatif.',
        ],
        'urutan' => [
            'required'          => 'Urutan wajib diisi.',
            'numeric'           => 'Urutan harus berupa angka.',
            'greater_than_equal_to' => 'Urutan tidak boleh negatif.',
        ],
    ];

    public function getAllData()
    {
        return $this->orderBy('urutan', 'ASC')->findAll();
    }

    public function getActiveData()
    {
        return $this->where('is_aktif', 1)->orderBy('urutan', 'ASC')->findAll();
    }
}
