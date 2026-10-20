<?php

namespace App\Modules\SMS\Models;

use CodeIgniter\Model;

class KategoriPrestasiModel extends Model
{
    protected $table            = 'refkategoriprestasi';
    protected $primaryKey       = 'id_kategori_prestasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'nama_kategori', 'kode_kategori', 'deskripsi', 'urutan', 'is_aktif'
    ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4
    protected $validationRules = [
        'nama_kategori'       => 'required|min_length[2]|max_length[100]',
        'kode_kategori'       => 'required|min_length[1]|max_length[20]',
        'deskripsi'           => 'permit_empty|max_length[500]',
        'urutan'             => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'nama_kategori' => [
            'required'   => 'Nama kategori prestasi wajib diisi.',
            'min_length' => 'Nama kategori prestasi minimal 2 karakter.',
            'max_length' => 'Nama kategori prestasi maksimal 100 karakter.',
        ],
        'kode_kategori' => [
            'required'   => 'Kode kategori prestasi wajib diisi.',
            'min_length' => 'Kode kategori prestasi minimal 1 karakter.',
            'max_length' => 'Kode kategori prestasi maksimal 20 karakter.',
        ],
        'deskripsi' => [
            'max_length' => 'Deskripsi maksimal 500 karakter.',
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
