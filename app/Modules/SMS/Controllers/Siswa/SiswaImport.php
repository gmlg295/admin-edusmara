<?php namespace App\Controllers\Siswa;

use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception as ReaderException;

class SiswaImport extends ResourceController
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Upload dan parse file Excel
     */
    public function upload()
    {
        $file = $this->request->getFile('excel_file');
        
        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'error',
                'message' => 'File tidak valid atau tidak ditemukan'
            ]);
        }

        // Validasi ekstensi
        $ext = strtolower($file->getClientExtension());
        if (!in_array($ext, ['xlsx', 'xls'])) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'error',
                'message' => 'Format file harus .xlsx atau .xls'
            ]);
        }

        try {
            // Baca file Excel
            $reader = IOFactory::createReaderForFile($file->getTempName());
            $spreadsheet = $reader->load($file->getTempName());
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Convert ke array
            $data = [];
            $headers = [];
            $rowCount = 0;
            
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(false);
                
                $colIndex = 0;
                foreach ($cellIterator as $cell) {
                    $value = $cell->getValue();
                    
                    // Baris pertama sebagai header
                    if ($rowCount === 0) {
                        $headers[] = trim($value ?? '');
                    } else {
                        // Handle date values
                        if (\PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                            $value = $cell->getValue();
                            if (is_numeric($value)) {
                                $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
                            }
                        }
                        $rowData[$colIndex] = $value;
                    }
                    $colIndex++;
                }
                
                if ($rowCount === 0) {
                    $rowCount++;
                    continue;
                }
                
                // Skip baris kosong
                if (!empty(array_filter($rowData))) {
                    $data[] = $rowData;
                }
            }

            // Mapping data berdasarkan header
            $mappedData = $this->mapDataToColumns($headers, $data);
            
            // Validasi kolom wajib
            $validationResult = $this->validateRequiredFields($mappedData);
            
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'success',
                'message' => 'File berhasil diproses',
                'data' => $mappedData,
                'validation' => $validationResult,
                'total_rows' => count($mappedData)
            ]);

        } catch (ReaderException $e) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'error',
                'message' => 'Error membaca file: ' . $e->getMessage()
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mapping data dari Excel ke kolom database
     */
    private function mapDataToColumns($headers, $data)
    {
        // Mapping header Excel ke kolom database
        // Sesuaikan dengan urutan kolom di template Excel kamu
        $columnMapping = [
            0 => 'nisn',
            1 => 'nis',
            2 => 'nama_lengkap',
            3 => 'jenis_kelamin',
            4 => 'tempat_lahir',
            5 => 'tanggal_lahir',
            6 => 'agama',
            7 => 'nomor_hp',
            8 => 'email',
            9 => 'alamat_lengkap',
            10 => 'nama_ayah',
            11 => 'nama_ibu',
            12 => 'pekerjaan_ayah',
            13 => 'pekerjaan_ibu',
            14 => 'no_hp_wali',
            15 => 'hubungan_wali',
            16 => 'tanggal_masuk',
            17 => 'jalur_masuk',
            18 => 'status_siswa',
        ];

        $mappedData = [];
        
        foreach ($data as $rowIndex => $row) {
            $record = [
                'row_number' => $rowIndex + 2, // +2 karena header row 1 dan array mulai dari 0
                'errors' => [],
                'data' => []
            ];

            foreach ($columnMapping as $colIndex => $columnName) {
                $value = isset($row[$colIndex]) ? trim($row[$colIndex] ?? '') : '';
                
                // Normalisasi data
                $value = $this->normalizeValue($columnName, $value);
                
                $record['data'][$columnName] = $value;
            }

            $mappedData[] = $record;
        }

        return $mappedData;
    }

    /**
     * Normalisasi nilai berdasarkan tipe kolom
     */
    private function normalizeValue($columnName, $value)
    {
        if (empty($value)) {
            return null;
        }

        switch ($columnName) {
            case 'jenis_kelamin':
                // Konversi L/P atau Laki-laki/Perempuan
                $value = strtoupper(substr($value, 0, 1));
                return in_array($value, ['L', 'P']) ? $value : null;
                
            case 'tanggal_lahir':
            case 'tanggal_masuk':
                // Format tanggal YYYY-MM-DD
                if (preg_match('/^\d{2}[-\/]\d{2}[-\/]\d{4}$/', $value)) {
                    $date = \DateTime::createFromFormat('d/m/Y', str_replace('-', '/', $value));
                    return $date ? $date->format('Y-m-d') : null;
                } elseif (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
                    return $value;
                }
                return null;
                
            case 'status_siswa':
                $value = strtolower(trim($value));
                $validStatus = ['aktif', 'pindah', 'lulus', 'dikeluarkan', 'keluar', 'nonaktif'];
                return in_array($value, $validStatus) ? $value : 'aktif';
                
            default:
                return $value;
        }
    }

    /**
     * Validasi kolom wajib
     */
    private function validateRequiredFields($mappedData)
    {
        // Kolom wajib untuk uji coba
        $requiredFields = [
            'nisn' => 'NISN',
            'nama_lengkap' => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin',
            'tanggal_lahir' => 'Tanggal Lahir',
            'tanggal_masuk' => 'Tanggal Masuk',
        ];

        $hasErrors = false;
        
        foreach ($mappedData as &$record) {
            $record['errors'] = [];
            
            foreach ($requiredFields as $field => $label) {
                $value = $record['data'][$field] ?? null;
                
                if (empty($value)) {
                    $record['errors'][] = $field;
                    $hasErrors = true;
                }
            }
        }

        return [
            'has_errors' => $hasErrors,
            'required_fields' => array_keys($requiredFields),
            'required_labels' => $requiredFields
        ];
    }

    public function saveRR()
    {
        // 1. Ambil raw body secara manual dari PHP stream
        $rawBody = file_get_contents('php://input');
        
        // 2. Cek panjangnya
        $length = strlen($rawBody);
        
        // 3. Coba decode
        $data = json_decode($rawBody, true);
        $jsonError = json_last_error_msg();

        // 4. Return hasil debug ke browser
        return $this->response->setJSON([
            'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
            'status' => 'debug',
            'body_length' => $length,
            'json_error' => $jsonError,
            'is_decoded' => !empty($data),
            'preview_body' => substr($rawBody, 0, 500), // Lihat 500 karakter pertama
            'decoded_data_sample' => $data ? array_slice($data, 0, 1) : null
        ]);
    }

    public function saveFilee()
{
    // Gunakan cara manual ini agar lebih stabil untuk API
    $json = file_get_contents('php://input');
    $input = json_decode($json, true);

    if (!$input || !isset($input['data'])) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Format data tidak dikenali.'
        ]);
    }

    $records = $input['data'];
    $saved = 0;

    foreach ($records as $record) {
        // Skip jika ada error validasi dari frontend
        if (!empty($record['errors'])) continue;

        $dataToInsert = [
            'nisn' => $record['data']['nisn'],
            'nama_lengkap' => $record['data']['nama_lengkap'],
            // ... mapping kolom lainnya
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('mstsiswa')->insert($dataToInsert);
        $saved++;
    }

    return $this->response->setJSON([
        'status' => 'success',
        'message' => "Berhasil menyimpan {$saved} data."
    ]);
}

    /**
     * Simpan data ke database
     */
    public function save()
    {
            $json = file_get_contents('php://input');
            $input = json_decode($json, true);

            if (!$input || !isset($input['data'])) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Format data tidak dikenali.'
                ]);
            }

        $records = $input['data'];
        $saved = 0;
        $failed = 0;
        $errors = [];

        $this->db->transStart();

        foreach ($records as $index => $record) {
            // Skip jika ada error validasi
            if (!empty($record['errors'])) {
                $failed++;
                $errors[] = "Baris {$record['row_number']}: Kolom wajib kosong";
                continue;
            }

            try {
                // Cek NISN duplicate
                $existing = $this->db->table('mstsiswa')
                    ->where('nisn', $record['data']['nisn'])
                    ->get()
                    ->getRow();

                if ($existing) {
                    $failed++;
                    $errors[] = "Baris {$record['row_number']}: NISN {$record['data']['nisn']} sudah ada";
                    continue;
                }

                // Siapkan data untuk insert
                $insertData = [
                    'nisn' => $record['data']['nisn'],
                    'nis' => $record['data']['nis'] ?? null,
                    'id_kelas' => 1, // Default kelas, sesuaikan dengan logic kamu
                    'nama_lengkap' => $record['data']['nama_lengkap'],
                    'jenis_kelamin' => $record['data']['jenis_kelamin'],
                    'tempat_lahir' => $record['data']['tempat_lahir'] ?? null,
                    'tanggal_lahir' => $record['data']['tanggal_lahir'],
                    'agama' => $record['data']['agama'] ?? null,
                    'nomor_hp' => $record['data']['nomor_hp'] ?? null,
                    'email' => $record['data']['email'] ?? null,
                    'alamat_lengkap' => $record['data']['alamat_lengkap'] ?? '-',
                    'nama_ayah' => $record['data']['nama_ayah'] ?? null,
                    'nama_ibu' => $record['data']['nama_ibu'] ?? null,
                    'pekerjaan_ayah' => $record['data']['pekerjaan_ayah'] ?? null,
                    'pekerjaan_ibu' => $record['data']['pekerjaan_ibu'] ?? null,
                    'no_hp_wali' => $record['data']['no_hp_wali'] ?? null,
                    'hubungan_wali' => $record['data']['hubungan_wali'] ?? null,
                    'tanggal_masuk' => $record['data']['tanggal_masuk'],
                    'jalur_masuk' => $record['data']['jalur_masuk'] ?? 'Umum',
                    'status_siswa' => $record['data']['status_siswa'] ?? 'aktif',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $this->db->table('mstsiswa')->insert($insertData);
                $saved++;

            } catch (\Exception $e) {
                $failed++;
                $errors[] = "Baris {$record['row_number']}: " . $e->getMessage();
            }
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'status' => 'error',
                'message' => 'Gagal menyimpan data',
                'errors' => $errors
            ]);
        }

        return $this->response->setJSON([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'status' => 'success',
            'message' => "Berhasil menyimpan {$saved} data. Gagal: {$failed}",
            'saved' => $saved,
            'failed' => $failed,
            'errors' => $errors
        ]);
    }
}