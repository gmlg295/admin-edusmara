<?php
namespace App\Controllers\Api\Sampel;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class Product extends ResourceController
{
    use ResponseTrait;

    protected $allowedYears = [];

    public function __construct()
    {
        // Izinkan tahun dari 2020 hingga tahun depan (misal: 2026 → izinkan sampai 2027)
        $currentYear = (int) date('Y');
        $this->allowedYears = range(2020, $currentYear + 1);
    }

    public function index()
    {
        
        return $this->respond([
            'status' => 200,
            'message' => 'Akses berhasil- ',
            'products' => [] // Ganti dengan query produk sesuai ID_produc
        ]);
    }
}