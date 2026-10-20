<?php

namespace App\Modules\SMS\Controllers\Sanksi;
use CodeIgniter\RESTful\ResourceController;

class Sanksi extends ResourceController
{
    protected $modelName = 'App\Modules\SMS\Models\SanksiModel';
    protected $format    = 'json';

    // GET /sanksi - Menampilkan halaman
    public function index()
    {
        return view('App\Modules\SMS\Views\content\master\master_sanksi', [
            'title' => 'Sanksi - EDUSMARA',
            'active' => 'sanksi',
            'load_js' => 'page/master/sanksi.js',
            'load_css' => 'page/master/sanksi-style.css',
            'enable_menu' => false,
        ]);
    }

    // GET /sanksi/getData - Mengambil data
    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->getAllData(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }

    // POST /sanksi - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getJSON(true) ?? [];

        // Custom validation untuk kode_sanksi unique
        $existing = $this->model->where('kode_sanksi', $input['kode_sanksi'])->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_sanksi' => 'Kode sanksi sudah digunakan.'],
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

    // PUT/PATCH /sanksi/{id} - Update Data
    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? [];

        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID sanksi tidak valid']);
        }

        // Custom validation untuk kode_sanksi unique (kecuali ID yang sedang diupdate)
        $existing = $this->model->where('kode_sanksi', $input['kode_sanksi'])
                               ->where('id_sanksi !=', $id)
                               ->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_sanksi' => 'Kode sanksi sudah digunakan.'],
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

    // DELETE /sanksi/{id} - Hapus Data
    public function delete($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID sanksi tidak valid']);
        }
        $this->model->where('id_sanksi', $id)->delete();
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }
}
