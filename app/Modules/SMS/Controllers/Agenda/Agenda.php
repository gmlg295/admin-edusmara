<?php

namespace App\Modules\SMS\Controllers\Agenda;
use CodeIgniter\RESTful\ResourceController;

class Agenda extends ResourceController
{
    protected $modelName = 'App\Models\AgendaModel';
    protected $format    = 'json';

    // GET /kelas - Mengambil data dengan Pagination & Search
     public function index()
    {
        return view('App\Modules\SMS\Views\content\master\master_agenda', [
            'title' => 'Agenda - EDUSMARA',
            'active' => 'agenda',
            'load_js' => 'page/master/agenda.js',
            'load_css' => 'page/master/pengumuman-style.css',
            'enable_menu' => false,
        ]);
    }

    public function getData()
    {
        return $this->respond([
            'status'  => true,
            'kategori' => $this->model->getKategori(),
            'data'    => $this->model->getAllData(),
            'total'   => $this->model->countAllResults(false)
        ]);
    }
    
    public function getKategori()
    {
        return $this->respond([
            'status'  => true,
            'kategori' => $this->model->getKategori(),
        ]);
    }

    // POST /kelas - Simpan Data Baru
    public function create()
    {
        $input = $this->request->getPost() ?? []; // Ambil data JSON$this->request->getPost();
        
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

        $files = $this->request->getFile('files');
        if ($files && $files->isValid() && !$files->hasMoved()) {
            $newName = $files->getRandomName();
            $files->move('./public/bank/sampel/', $newName);
            $input['gambar_header'] = $newName; // Set nama file ke input
        } else {
            $input['gambar_header'] = null; // Jika tidak ada file, set null
        }

        $input['id_penulis'] = session()->get('user_id'); // Set id_penulis dari session
        $input['id_kategori_informasi'] = (int) $input['kategori'] ?? 1; // Set id_kategori_informasi dari request
        $input['status'] = $input['status'] ?? 'draft'; // Set status dari request, default 'draft'
        $input['slug'] = url_title($input['judul'], '-', true); // Generate slug dari judul
        $input['ringkasan'] = substr(strip_tags($input['konten']), 0, 100); // Generate ringkasan dari konten
        $input['gambar_header'] = $input['gambar_header'] ?? null; // Set gambar_header dari request
        $input['jumlah_dibaca'] = 0; // Set jumlah_dibaca
        $input['jumlah_disukai'] = 0; // Set jumlah_disukai
        $input['published_at'] = ($input['status'] === 'publish') ? date('Y-m-d H:i:s') : null; // Set published_at jika status publish
        $input['archived_at'] = ($input['status'] === 'archive') ? date('Y-m-d H:i:s') : null; // Set archived_at jika status archive   


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
        $input = $this->request->getPost() ?? [];

        if (!$id || !is_numeric($id)) {
            return $this->failValidationErrors(['id' => 'ID kelas tidak valid']);
        }

        if($input['isUpdateFile'] === 'true') {
            $files = $this->request->getFile('files');
            if ($files && $files->isValid() && !$files->hasMoved()) {
                $newName = $files->getRandomName();
                $files->move('./public/bank/sampel/', $newName);
                $input['gambar_header'] = $newName; // Set nama file ke input
            } else {
                $input['gambar_header'] = null; // Jika tidak ada file, set null
            }
        } else {
            unset($input['gambar_header']); // Jangan update gambar_header jika tidak ada file baru
        }

        $input['id_penulis'] = session()->get('user_id'); // Set id_penulis dari session
        $input['id_kategori_informasi'] = (int) $input['kategori'] ?? 1; // Set id_kategori_informasi dari request
        $input['status'] = $input['status'] ?? 'draft'; // Set status dari request, default 'draft'
        $input['slug'] = url_title($input['judul'], '-', true); // Generate slug dari judul
        $input['ringkasan'] = substr(strip_tags($input['konten']), 0, 100); // Generate ringkasan dari konten
        $input['gambar_header'] = $input['gambar_header'] ?? null; // Set gambar_header dari request
        $input['jumlah_dibaca'] = 0; // Set jumlah_dibaca
        $input['jumlah_disukai'] = 0; // Set jumlah_disukai
        $input['published_at'] = ($input['status'] === 'publish') ? date('Y-m-d H:i:s') : null; // Set published_at jika status publish
        $input['archived_at'] = ($input['status'] === 'archive') ? date('Y-m-d H:i:s') : null; // Set archived_at jika status archive   

        
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
        $this->model->where('id_informasi', $id)->delete();        
        return $this->respondDeleted(['status' => true, 'message' => 'Data berhasil dihapus']);
    }

    private function setFiles()
	{
            $pesan['pesan'] = "";
            $pesan['status'] = false;
            $pesan['nama'] = "";
            $pesan['ext'] = "";
            $validated = $this->validate([
                'files' => [
                    'uploaded[files]',
                    'mime_in[files,image/png,image/jpg,image/jpeg]',
                    'ext_in[files,png,jpg,jpeg]',
                    'max_size[files,8096]',
                    'errors' => [
                        'uploaded[files]' => 'Tidak ada berkas yg dipilih.'
                    ]
                ],
            ]);
            if (!$validated) {
                $pesan['pesan'] = service('validation')->getErrors();
            }else{
                if ($files = $this->request->getFile('files')) {
                        if ($files->isValid() && ! $files->hasMoved()) {
                            $newName = $files->getRandomName();
                            if (!file_exists('public/bank/sampel/'.$newName)) {
                                $files->move('./public/bank/sampel/', $newName);
                                $pesan['pesan'] = "Proses Unggah Berhasil";
                                $pesan['status'] = true;

                            }else{
                                $pesan['pesan'] = "Proses Unggah Berhasil";
                                $pesan['status'] = true;
                            }
                            $pesan['nama'] = $newName;
                            $pesan['ext'] = "";
                        }else{
                            $pesan['pesan'] = "file tidak sesuai!";
                        }
                }
        
            
        }
        return $pesan;

	}
}