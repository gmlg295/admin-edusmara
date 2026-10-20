<?php

namespace App\Modules\SMS\Models;

use CodeIgniter\Model;

class AlasanTerlambatModel extends Model
{
    protected $table            = 'refalasanterlambat';
    protected $primaryKey       = 'id_alasan_terlambat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nama_alasan', 'urutan', 'is_aktif'
    ];

    // created_at diisi otomatis oleh DEFAULT CURRENT_TIMESTAMP di database
    protected $useTimestamps    = false;

    // Validasi CI4
    protected $validationRules = [
        'nama_alasan' => 'required|min_length[2]|max_length[100]',
        'urutan'      => 'required|integer|greater_than_equal_to[0]|less_than_equal_to[127]',
        'is_aktif'    => 'permit_empty|in_list[0,1]',
    ];

    protected $validationMessages = [
        'nama_alasan' => [
            'required'   => 'Nama alasan wajib diisi.',
            'min_length' => 'Nama alasan minimal 2 karakter.',
            'max_length' => 'Nama alasan maksimal 100 karakter.',
        ],
        'urutan' => [
            'required'              => 'Urutan wajib diisi.',
            'integer'               => 'Urutan harus berupa angka bulat.',
            'greater_than_equal_to' => 'Urutan tidak boleh negatif.',
            'less_than_equal_to'    => 'Urutan maksimal 127.',
        ],
        'is_aktif' => [
            'in_list' => 'Status hanya boleh Aktif atau Non-Aktif.',
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
