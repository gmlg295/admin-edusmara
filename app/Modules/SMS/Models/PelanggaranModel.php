<?php

namespace App\Modules\SMS\Models;

use CodeIgniter\Model;

class PelanggaranModel extends Model
{
    protected $table            = 'mstguru';
    protected $primaryKey       = 'id_guru';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields = false;
    //protected $allowedFields    = [];
    
    // [
    //     'nip', 'nuptk', 'nama_lengkap', 'nama_tanpa_gelar', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'alamat', 'no_hp', 'email', 'pendidikan_terakhir', 'tahun_lulus', 'status_kepegawaian', 'jabatan_fungsional', 'tugas_tambahan', 'foto', 'is_aktif', 'created_by', 'created_at', 'updated_by', 'updated_at'
    // ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4 [[7]]
    protected $validationRules = [
        'nama_lengkap'          => 'required|min_length[2]|max_length[20]',
        'jenis_kelamin'         => 'required|min_length[1]|in_list[L,P]',
        'tempat_lahir'          => 'required|min_length[2]|max_length[40]',
        'tanggal_lahir'         => 'required|valid_date[Y-m-d]',
        'alamat_lengkap'        => 'required|min_length[2]|max_length[50]',
        'jabatan_struktural'     => 'required|min_length[2]|max_length[50]',
        'status_kepegawaian'     => 'required|min_length[2]|max_length[10]',
        'golongan_pangkat'       => 'required|min_length[2]|max_length[50]',
        'status_pegawai'           => 'required|min_length[2]|max_length[10]',
        'universitas'           => 'required|min_length[2]|max_length[50]',
        'jurusan'               => 'required|min_length[2]|max_length[50]',
        'jenjang_pendidikan'       => 'required|min_length[2]|max_length[3]',
        'tahun_lulus'           => 'required|integer|greater_than[1990]',
        'sertifikasi_guru'       => 'required|min_length[1]|in_list[1,0]',
        'nip'                   => 'permit_empty|max_length[20]',
        'nuptk'                 => 'permit_empty|max_length[20]',
        //'role'                  => 'permit_empty|in_list[admin,kepsek,wakasek,guru,bk,tu,pustakawan,laboran]',
        'nomor_hp'              => 'permit_empty|max_length[16]',
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
        return $this->db->query("SELECT a.id_pelanggaran, a.id_siswa, a.id_jenis_pelanggaran, a.id_sub_jenis, a.id_tingkat_kasus, a.id_sanksi, a.id_alasan_terlambat, a.tanggal_kejadian,
        a.jam_masuk_sekolah, a.jam_tiba_sekolah, a.durasi_bolos, a.keterangan, a.diketahui_oleh, a.dicatat_oleh, a.status, a.catatan_tindak_lanjut,
        b.nama_lengkap, b.nisn, b.nis, b.jenis_kelamin, c.tingkat, c.nama_kelas,
        d.nama_jenis, d.kode_jenis, e.nama_sub_jenis, f.nama_tingkat, f.kode_tingkat, f.bobot_poin, g.nama_sanksi, g.kode_sanksi

        FROM refpelanggaransiswa a 
        INNER JOIN mstsiswa b ON a.`id_siswa`=b.`id_siswa`
        LEFT JOIN mstkelas c ON	b.`id_kelas`=c.`id_kelas`	
        INNER JOIN refjenispelanggaran d ON a.`id_jenis_pelanggaran`=d.`id_jenis_pelanggaran`
        LEFT JOIN refsubjenispelanggaran e ON a.`id_sub_jenis`=e.`id_sub_jenis`
        LEFT JOIN reftingkatkasus f ON a.`id_tingkat_kasus`=f.`id_tingkat_kasus`
        LEFT JOIN refsanksi g ON a.`id_sanksi`=g.`id_sanksi` ORDER BY a.`id_pelanggaran` DESC;")->getResult();
    }
    
    public function getdataJenisPelanggaran()
    {
        $data = $this->db->query("SELECT * FROM refjenispelanggaran ORDER BY urutan ASC;")->getResult();
        $array = [];
        foreach ($data as $r) {
           $array[$r->kode_jenis] = ['id' => $r->id_jenis_pelanggaran, 'label' => $r->nama_jenis, 'desc' => $r->deskripsi];
        }
        return $data;
    }
    public function getdataSubJenisPelanggaran()
    {
        return $this->db->query("SELECT * FROM refsubjenispelanggaran ORDER BY urutan ASC;")->getResult();
    }
    
    public function getdataAlasanTerlambat()
    {
        return $this->db->query("SELECT * FROM refalasanterlambat ORDER BY urutan ASC;")->getResult();
    }
    
    public function getdataTingkatKasus()
    {
        return $this->db->query("SELECT * FROM reftingkatkasus ORDER BY urutan ASC;")->getResult();
    }

    public function getdataSanksi()
    {
        return $this->db->query("SELECT * FROM refsanksi ORDER BY urutan ASC;")->getResult();
    }

    public function getdataSiswa($id_kelas = 0)
    {
        return $this->db->query("SELECT id_siswa, nama_lengkap, nisn, nis, jenis_kelamin, tingkat, nama_kelas FROM mstsiswa inner join mstkelas on mstsiswa.`id_kelas`=mstkelas.`id_kelas` WHERE mstkelas.`id_kelas`='{$id_kelas}' ORDER BY mstkelas.`tingkat`, mstkelas.`nama_kelas`, mstsiswa.`nama_lengkap` ASC;")->getResult();
    }
    
    public function getdataKelas()
    {
        return $this->db->query("SELECT nama_kelas, id_kelas, tingkat FROM mstkelas ORDER BY tingkat, nama_kelas ASC;")->getResult();
    }
}