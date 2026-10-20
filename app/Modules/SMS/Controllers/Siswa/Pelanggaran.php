<?php

namespace App\Modules\SMS\Controllers\Siswa;
use CodeIgniter\RESTful\ResourceController;

class Pelanggaran extends ResourceController
{
    protected $modelName = 'App\Modules\SMS\Models\PelanggaranModel';
    protected $format    = 'json';

    // GET /kelas - Mengambil data dengan Pagination & Search
     public function index()
    {
        return view('App\Modules\SMS\Views\content\siswa\pelanggaran_siswa', [
            'title' => 'Pelanggaran Siswa - EDUSMARA',
            'active' => 'master',
            'load_js' => 'page/siswa/pelanggaran_siswa.js',
            'load_css' => 'page/siswa/pelanggaran-style.css',
            'enable_menu' => false,
        ]);
    }

    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'data'    => $this->model->getAllData(),
        ]);
    }
    
    public function getDataConfig()
    {
        return $this->respond([
            'status'  => true,
            'jenis'    => $this->model->getdataJenisPelanggaran(),
            'sub_jenis'    => $this->model->getdataSubJenisPelanggaran(),
            'kelas'    => $this->model->getdataKelas(),
            'tingkat_kasus'    => $this->model->getDataTingkatKasus(),
            'terlambat'    => $this->model->getDataAlasanTerlambat(),
            'sanksi'    => $this->model->getDataSanksi(),
        ]);
    }

    public function getdataSiswa()
    {
        $kelas = $this->request->getGet('kelas');
        if ($kelas != null && is_numeric($kelas)) {
            return $this->respond([
                'status'  => true,
                'data'    => $this->model->getdataSiswa($kelas),
            ]);
        }

        return $this->respond([
            'status'  => false,
            'data'    => [],
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