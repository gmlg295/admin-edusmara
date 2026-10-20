<?php

namespace App\Modules\SMS\Controllers\TingkatKasus;
use CodeIgniter\RESTful\ResourceController;

class TingkatKasus extends ResourceController
{
    protected $modelName = 'App\Modules\SMS\Models\TingkatKasusModel';
    protected $format    = 'json';

    // GET /tingkatkasus - Menampilkan halaman
    public function index()
    {
        return view('App\Modules\SMS\Views\content\master\master_tingkatkasus', [
            'title' => 'Tingkat Kasus - EDUSMARA',
            'active' => 'tingkatkasus',
            'load_js' => 'page/master/tingkatkasus.js',
            'load_css' => 'page/master/tingkatkasus-style.css',
            'enable_menu' => false,
        ]); 
    }

    // GET /tingkatkasus/getData - Mengambil data
    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->getAllData(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }

    // POST /tingkatkasus - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getJSON(true) ?? [];

        // Custom validation untuk kode_tingkat unique
        $existing = $this->model->where('kode_tingkat', $input['kode_tingkat'])->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_tingkat' => 'Kode tingkat kasus sudah digunakan.'],
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

    // PUT/PATCH /tingkatkasus/{id} - Update Data
    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? [];

        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID tingkat kasus tidak valid']);
        }

        // Custom validation untuk kode_tingkat unique (kecuali ID yang sedang diupdate)
        $existing = $this->model->where('kode_tingkat', $input['kode_tingkat'])
                               ->where('id_tingkat_kasus !=', $id)
                               ->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['kode_tingkat' => 'Kode tingkat kasus sudah digunakan.'],
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

    // DELETE /tingkatkasus/{id} - Hapus Data
    public function delete($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID tingkat kasus tidak valid']);
        }
        $this->model->where('id_tingkat_kasus', $id)->delete();
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }
}
