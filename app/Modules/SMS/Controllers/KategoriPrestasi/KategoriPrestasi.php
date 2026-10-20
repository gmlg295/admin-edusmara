<?php

namespace App\Modules\SMS\Controllers\KategoriPrestasi;
use CodeIgniter\RESTful\ResourceController;

class KategoriPrestasi extends ResourceController
{
    protected $modelName = 'App\Modules\SMS\Models\KategoriPrestasiModel';
    protected $format    = 'json';

    // GET /kategoriprestasi - Menampilkan halaman
    public function index()
    {
        return view('App\Modules\SMS\Views\content\master\master_kategoriprestasi', [
            'title' => 'Kategori Prestasi - EDUSMARA',
            'active' => 'kategoriprestasi',
            'load_js' => 'page/master/kategoriprestasi.js',
            'load_css' => 'page/master/kategoriprestasi-style.css',
            'enable_menu' => false,
        ]);
    }

    // GET /kategoriprestasi/getData - Mengambil data
    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->getAllData(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }

    // POST /kategoriprestasi - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getJSON(true) ?? [];

        // Custom validation untuk kode_kategori unique
        $existing = $this->model->where('kode_kategori', $input['kode_kategori'])->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_kategori' => 'Kode kategori prestasi sudah digunakan.'],
            ]);
        }

        if (!$this->validate($this->model->validationRules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $input['is_aktif'] = $input['is_aktif'] ?? 1; // Default aktif

        $id = $this->model->insert($input);
        return $this->response->setStatusCode(201)->setJSON([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    // PUT/PATCH /kategoriprestasi/{id} - Update Data
    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? [];

        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID kategori prestasi tidak valid']);
        }

        // Custom validation untuk kode_kategori unique (kecuali ID yang sedang diupdate)
        $existing = $this->model->where('kode_kategori', $input['kode_kategori'])
                               ->where('id_kategori_prestasi !=', $id)
                               ->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_kategori' => 'Kode kategori prestasi sudah digunakan.'],
            ]);
        }

        $input['is_aktif'] = $input['is_aktif'] ?? 1;

        if (!$this->validate($this->model->validationRules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        $this->model->update($id, $input);
        return $this->respondUpdated([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }

    // DELETE /kategoriprestasi/{id} - Hapus Data
    public function delete($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID kategori prestasi tidak valid']);
        }
        $this->model->where('id_kategori_prestasi', $id)->delete();
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }
}
