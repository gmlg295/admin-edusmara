<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'refinformasi';
    protected $primaryKey       = 'id_informasi';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'judul', 'slug', 'konten', 'ringkasan',
        'gambar_header', 'tanggal_publikasi', 'target_pembaca', 'prioritas',
        'status', 'published_at', 'archived_at', 'created_by','id_kategori_informasi', 'id_penulis',
        'jumlah_dibaca', 'jumlah_disukai', 'jumlah_komentar', 'catatan_admin',
    ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4 [[7]]
    protected $validationRules = [
        'judul'             => 'required|min_length[2]|max_length[300]',
        'konten'            => 'required|min_length[2]|max_length[5000]',
        'gambar_header'     => 'permit_empty|max_length[100]',
        'tanggal_publikasi'   => 'required|valid_date[Y-m-d]',
        'target_pembaca'      => 'required|max_length[10]',
        'prioritas'         => 'required|max_length[20]',
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
        return $this->db->query("SELECT a.*, b.nama_kategori as kategori, c.nama_lengkap as penulis FROM `refinformasi` a INNER JOIN refkategoriinformasi b ON a.`id_kategori_informasi` = b.`id_kategori_informasi` INNER JOIN refauth c ON	a.`id_penulis`=c.`user_id` ORDER BY a.created_at ASC;")->getResult();
    }
    
    public function getKategori()
    {
        return $this->db->query("SELECT * FROM refkategoriinformasi WHERE is_aktif=1 order by urutan ASC")->getResult();
    }
}