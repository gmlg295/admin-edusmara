<?php

namespace App\Controllers\Master;
use CodeIgniter\RESTful\ResourceController;

class Guru extends ResourceController
{
    protected $modelName = 'App\Models\GuruModel';
    protected $format    = 'json';

    // GET /kelas - Mengambil data dengan Pagination & Search
    public function index()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->findAll(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }

    // POST /kelas - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getJSON(true) ?? []; // Ambil data JSON$this->request->getPost();
        
        if (!$this->validate($this->model->validationRules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);

            //return $this->failValidationErrors($this->validator->getErrors());
        }

        $id = $this->model->insert($input);
        return $this->response->setStatusCode(201)->setJSON([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => true, 
            'message' => 'Data berhasil disimpan'
            ]);
    }

    // PUT/PATCH /kelas/{id} - Update Data
    public function update($id = null)
    {
        $input = $this->request->getJSON(true) ?? [];
        
        if (!$this->validate($this->model->validationRules)) {
            return $this->response->setStatusCode(422)->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors(),
            ]);
            //return $this->failValidationErrors($this->validator->getErrors());
        }

        $this->model->update($id, $input);
        return $this->respondUpdated([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => true, 
                'message' => 'Data berhasil diperbarui'
            ]);
    }

    // DELETE /kelas/{id} - Hapus Data
    public function delete($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID kelas tidak valid']);
        }
        $this->model->where('id_kelas', $id)->delete();
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }
}