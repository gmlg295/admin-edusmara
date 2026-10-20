<?php

namespace App\Modules\SMS\Controllers\AlasanTerlambat;
use CodeIgniter\RESTful\ResourceController;

class AlasanTerlambat extends ResourceController
{
    protected $modelName = 'App\Modules\SMS\Models\AlasanTerlambatModel';
    protected $format    = 'json';

    // GET /alasanterlambat - Menampilkan halaman
    public function index()
    {
        return view('App\Modules\SMS\Views\content\master\master_alasanterlambat', [
            'title' => 'Alasan Terlambat - EDUSMARA',
            'active' => 'alasanterlambat',
            'load_js' => 'page/master/alasanterlambat.js',
            'load_css' => 'page/master/alasanterlambat-style.css',
            'enable_menu' => false,
        ]);
    }

    // GET /alasanterlambat/getData - Mengambil data
    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->getAllData(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }

    // POST /alasanterlambat/create - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getJSON(true) ?? [];

        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Custom validation untuk nama_alasan unique
        $existing = $this->model->where('nama_alasan', trim($input['nama_alasan']))->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['nama_alasan' => 'Alasan terlambat sudah ada.'],
            ]);
        }

        $input['nama_alasan'] = trim($input['nama_alasan']);
        $input['is_aktif'] = $input['is_aktif'] ?? 1; // Default aktif

        $this->model->insert($input);
        return $this->response->setStatusCode(201)->setJSON([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => true,
            'message' => 'Data berhasil disimpan'
        ]);
    }

    // POST /alasanterlambat/update/{id} - Update Data
    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? [];

        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID alasan terlambat tidak valid']);
        }

        if (!$this->validate($this->model->validationRules, $this->model->validationMessages)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);
        }

        // Custom validation untuk nama_alasan unique (kecuali ID yang sedang diupdate)
        $existing = $this->model->where('nama_alasan', trim($input['nama_alasan']))
                               ->where('id_alasan_terlambat !=', $id)
                               ->first();
        if ($existing) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => ['nama_alasan' => 'Alasan terlambat sudah ada.'],
            ]);
        }

        $input['nama_alasan'] = trim($input['nama_alasan']);
        $input['is_aktif'] = $input['is_aktif'] ?? 1;

        $this->model->update($id, $input);
        return $this->respondUpdated([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => true,
            'message' => 'Data berhasil diperbarui'
        ]);
    }

    // GET /alasanterlambat/delete/{id} - Hapus Data
    public function delete($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID alasan terlambat tidak valid']);
        }
        $this->model->where('id_alasan_terlambat', $id)->delete();
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }
}
