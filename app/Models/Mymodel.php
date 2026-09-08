<?php

namespace App\Models;

use CodeIgniter\Model;

class Mymodel extends Model
{
    public $status      = false;
    public $pesanError  = '';

    protected $table        = '';
    protected $primaryKey   = '';
    protected $returnType   = 'array';
    protected $useTimestamps = true;
    protected $DBGroup      = 'default';
    protected $builder;

    // Cache nama kolom agar tidak query database berulang-ulang
    private static $columnCache = [];

    public function __construct(array $config = [])
    {
        parent::__construct();
        $this->applyConfig($config);
        
        $this->db = \Config\Database::connect($this->DBGroup);
        
        if ($this->table !== '') {
            $this->builder = $this->db->table($this->table);
        }
    }
   
    public function applyConfig(array $config)
    {
        foreach ($config as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
        return $this;
    }

    /**
     * Ambil semua data
     * Note: CI4 Query Builder sudah otomatis escape identifier (backtick)
     */
    public function semua($limit = null, $offset = 0, $groupBy = null, $orderBy = null)
    {
        $builder = $this->builder;

        if (!empty($groupBy)) {
            $builder->groupBy($groupBy);
        }
        
        if (!empty($orderBy)) {
            // Biarkan CI4 menangani parsing order by
            // Tapi kita batasi direction hanya ASC/DESC untuk keamanan logika
            $parts = explode(' ', $orderBy);
            $direction = end($parts);
            
            if (in_array(strtoupper($direction), ['ASC', 'DESC'])) {
                $builder->orderBy($orderBy);
            } else {
                $builder->orderBy($orderBy, 'ASC');
            }
        }

        return $builder->get($limit, $offset)->getResult();
    }

    /**
     * Raw Query - Gunakan dengan hati-hati!
     * Selalu gunakan binding (?) untuk nilai variabel
     */
    public function raw($query, $bindings = [])
    {
        return $this->db->query($query, $bindings);
    }

    /**
     * Insert data
     */
    public function simpan($data, $func = "")
    {
        $this->status = $this->builder->insert($data);
        return $func . $this->pesan();
    }

    /**
     * Update data by primary key
     */
    public function ubah($data, $id)
    {
        return $this->builder->where($this->primaryKey, $id)->update($data);
    }

    public function updateMultiId($tabel, $data, $where, $func = '')
    { 
        // Pastikan tabel valid sebelum proses
        if (!$this->isValidTableName($tabel)) {
            throw new \InvalidArgumentException("Tabel tidak valid");
        }

        $query = $this->db->table($tabel); 
        $query->where($where); 
        $this->status = $query->update($data, $where); 
        return $func . $this->pesan(); 
    }

    /**
     * Data terbaru berdasarkan timestamp
     */
    public function berdasarkan($column = 'created_at')
    {
        // Validasi sederhana: pastikan hanya karakter alphanumeric dan underscore
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            $column = 'created_at';
        }
        
        return $this->builder
            ->orderBy($column, 'DESC')
            ->get()
            ->getResult();
    }

	public function tertentu($where = [], $kolom = '*', $grup = null, $order = null, $limit = null, $offset = 0)
	{
        // Validasi kolom select dengan regex cepat (tanpa query DB)
        if ($kolom !== '*' && !preg_match('/^[a-zA-Z0-9_,\s]+$/', $kolom)) {
            $kolom = '*';
        }

		$query = $this->builder->select($kolom)->where($where);
		
		if (isset($grup)) {
            // Validasi grup dengan regex
            if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $grup)) {
                $query->groupBy($grup);
            }
		}
		
		if (isset($order)) {
            // Validasi order dengan regex
            if (preg_match('/^[a-zA-Z_][a-zA-Z0-9_\s]*(ASC|DESC)?$/i', $order)) {
                $query->orderBy($order);
            }
		}
		
		return $query->get($limit, $offset);
	}
	
    public function satu($where)
    {
        return $this->builder->where($where)->get()->getRow();
    }

    public function banyak($where)
    {
        return $this->builder->where($where)->get()->getResult();
    }

    public function hapus($id)
    {
        return $this->builder->where($this->primaryKey, $id)->delete();
    }

    public function temukan($where)
    {
        return $this->builder->where($where)->get();
    }

    public function jumlah()
    {
        return $this->builder->countAllResults();
    }
    /**
     * Generate ID Unik Alfanumerik (Huruf & Angka)
     * 
     * @param int $length Panjang karakter ID
     * @param string $tb Nama tabel (opsional, default pakai table model ini)
     * @param string $column Nama kolom ID di database (default 'id')
     * @return string
     */
    public function newId($length = 8, $tb = '', $column = 'id')
    {
        $tb = $tb ?: $this->table;
        
        // Validasi nama tabel untuk keamanan
        if (!$this->isValidTableName($tb)) {
            throw new \InvalidArgumentException("Nama tabel tidak valid.");
        }

        // Mulai proses generate
        return $this->generateUniqueId($tb, $column, $length);
    }

    /**
     * Fungsi rekursif untuk memastikan ID benar-benar unik
     */
    private function generateUniqueId($tb, $column, $length)
    {
        // 1. Buat string acak gabungan huruf besar, kecil, dan angka
        $randomId = $this->createRandomString($length);

        // 2. Cek ketersediaan di database
        $exists = $this->db->table($tb)
                           ->where($column, $randomId)
                           ->countAllResults() > 0;

        // 3. Jika sudah ada, panggil fungsi ini lagi (rekursif) sampai dapat yang kosong
        if ($exists) {
            return $this->generateUniqueId($tb, $column, $length);
        }

        return $randomId;
    }

    /**
     * Helper untuk membuat string acak alfanumerik
     */
    private function createRandomString($length)
    {
        // Karakter yang diperbolehkan: A-Z, a-z, 0-9
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }

 
    
    /**
     * Validasi nama tabel dengan Regex (Sangat Cepat, tanpa query DB)
     */
    private function isValidTableName($table)
    {
        // Hanya izinkan huruf, angka, dan underscore. Tidak boleh ada spasi atau karakter khusus SQL
        return preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $table);
    }
}