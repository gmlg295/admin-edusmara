<?php

namespace App\Modules\SMS\Models;

use CodeIgniter\Model;

class AgendaModel extends Model
{
    protected $table            = 'refagendasekolah';
    protected $primaryKey       = 'id_agenda';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields    = [
        'id_jenis_agenda', 'judul', 'slug', 'deskripsi', 'gambar',
        'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai',
        'is_full_day', 'lokasi', 'target_peserta', 'target_kelas','participants',
        'estimasi_peserta', 'tampilkan_countdown', 'kirim_reminder',
        'reminder_sent_at', 'alasan_pembatalan','catatan_admin','tampilkan_countdown','kirim_reminder','status','published_at','cancelled_at', 
    ];

    // Timestamps otomatis
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';

    // Validasi CI4 [[7]]
    protected $validationRules = [
        'judul'             => 'required|min_length[2]|max_length[300]',
        'deskripsi'            => 'required|min_length[2]|max_length[5000]',
        'gambar'                => 'permit_empty|max_length[100]',
        'lokasi'            => 'required|min_length[2]|max_length[100]',
        'target_peserta'      => 'required|max_length[20]',
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
        $agenda = $this->db->query("SELECT a.*, b.nama_jenis, b.kode_jenis, b.ikon FROM refagendasekolah a INNER JOIN refjenisagenda b ON	a.`id_jenis_agenda`=b.`id_jenis_agenda`ORDER BY a.created_at ASC;")->getResult();
        $arr = [];

        // {id:1,title:'Rapat Koordinasi Panitia 17 Agustus',category:'rapat',dateStart:'2026-08-16',dateEnd:'',timeStart:'14:00',timeEnd:'16:00',fullDay:false,location:'Ruang Guru',target:'guru',estPeserta:12,participants:'Kepala Sekolah, Wakasek, Panitia HUT RI',desc:'Rapat koordinasi final persiapan upacara HUT RI ke-81. Membahas rundown acara, pembagian tugas, dan pengecekan peralatan.',priority:'tinggi',countdown:false,reminder:true,status:'upcoming',createdAt:'2026-08-10T09:00:00Z'},

        foreach ($agenda as $key => $value) {
            $arr[] = array(
                'id' =>(int) $value->id_agenda,
                'title' => $value->judul,
                'category' => $value->kode_jenis,
                'dateStart' => $value->tanggal_mulai,
                'dateEnd' => $value->tanggal_selesai,
                'timeStart' => $value->jam_mulai,
                'timeEnd' => $value->jam_selesai,
                'fullDay' => $value->is_full_day,
                'location' => $value->lokasi,
                'target' => $value->target_peserta,
                'estPeserta' => $value->estimasi_peserta,
                'participants' => $value->participants,
                'desc' => $value->deskripsi,
                'priority' => $value->status,
                'countdown' => $value->tampilkan_countdown,
                'reminder' => $value->kirim_reminder,
                'status' => $value->status,
                'createdAt' => $value->created_at
            );
        }

        return $arr;


    }
    
    public function getKategori()
    {
        return $this->db->query("SELECT id_jenis_agenda, nama_jenis, ikon, kode_jenis FROM refjenisagenda WHERE is_aktif=1 order by urutan ASC")->getResult();
    }
}