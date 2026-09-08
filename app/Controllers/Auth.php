<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Auth extends Controller
{
    protected $session;
    protected $db;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);
        $this->session = \Config\Services::session();
        $this->db      = \Config\Database::connect();
        //$this->session->set(['isLoggedIn'=>false]);
    }

    // -------------------------------------------------------
    //  HALAMAN LOGIN (GET)
    // -------------------------------------------------------
    public function index()
    {
        
        // Jika sudah login, redirect ke dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to(base_url('home'));
        }
        //$this->session->destroy();
        // Generate CAPTCHA awal dan simpan di session
        $this->generateCaptchaSession();

        return view('content/login', [
            'title' => 'Dashboard Admin - EDUSMARA',
            'load_js' => 'login.js',
        ]);
    }

    public function getDeviceConf(){
        $data = $this->setDeviceConf();
        return $this->response->setJSON([
                'data' => $data,
            ]);
    }



    // -------------------------------------------------------
    //  PROSES LOGIN (POST - AJAX JSON)
    // -------------------------------------------------------
    public function login()
    {
        helper('Fungsi_helper');
        // Hanya terima request AJAX / JSON
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => false,
                'message' => 'Invalid request method.'
            ]);
        }

        // Ambil data JSON dari body
        $data = $this->request->getJSON(true);

        $username     = trim($data['username'] ?? '');
        $password     = trim($data['password'] ?? '');
        $captchaInput = strtoupper(trim($data['captcha'] ?? ''));

        // --- VALIDASI INPUT ---
        if (empty($username) || empty($password) ) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => false,
                'message' => 'Semua field wajib diisi! '
            ]);
        }

        if($this->checkLockout()){
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => true,
                'message' => 'Anda telah terblokir. Silakan coba lagi dalam beberapa menit.'
            ]);
        }

        // --- VALIDASI CAPTCHA (Server-Side) ---
        // $storedCaptcha = $this->session->get('captcha_code');

        // if (empty($storedCaptcha)) {
        //     return $this->response->setJSON([
        //         'csrf_token' => csrf_token(),
        //         'csrf_hash' => csrf_hash(),
        //         'success' => false,
        //         'message' => 'Sesi CAPTCHA telah kadaluarsa. Silakan refresh.'
        //     ]);
        // }

        // if ($captchaInput !== strtoupper($storedCaptcha)) {
        //     // Regenerate CAPTCHA baru setelah gagal
        //     $this->generateCaptchaSession();

        //     return $this->response->setJSON([
        //         'csrf_token' => csrf_token(),
        //         'csrf_hash' => csrf_hash(),
        //         'success' => false,
        //         'message' => 'Kode keamanan tidak valid! Silakan coba lagi.'
        //     ]);
        // }

        // --- CEK RATE LIMIT (opsional: max 5 percobaan per menit) ---
        $loginAttempts = $this->session->get('login_attempts') ?? 0;
        if ($loginAttempts >= 5) {
            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => false,
                'message' => 'Terlalu banyak percobaan. Tunggu 60 detik.'
            ]);
        }

        // --- VERIFIKASI USER DI DATABASE ---
        $user = $this->db->table('refauth')
            ->where(['username'=>$username, 'is_active'=>1])
            ->get()
            ->getRowArray();

        if (!$user) {
            $this->incrementLoginAttempts();
           // $this->generateCaptchaSession();

            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => false,
                'message' => 'Username tidak ditemukan!',
                'login_attempts' => $this->session->getTempdata('login_attempts'),
            ]);
        }

        // Verifikasi password (gunakan password_verify untuk hashed password)
        if (!decPass($password, $user['password_hash'])) {
            $this->incrementLoginAttempts();
            //$this->generateCaptchaSession();

            return $this->response->setJSON([
                'csrf_token' => csrf_token(),
                'csrf_hash' => csrf_hash(),
                'success' => false,
                'lockout' => false,
                'message' => 'Kata sandi salah!'
            ]);
        }

        // --- LOGIN BERHASIL ---
        // Reset attempt counter
        $this->session->remove('login_attempts');

        // Simpan data user ke session
        $sessionData = [
            'isLoggedIn'    => true,
            'user_id'       => $user['user_id'],
            'username'      => $user['username'],
            'nama_lengkap'  => $user['nama_lengkap'],
            'role'          => $user['role'],
            'last_login'    => waktu(),
        ];
        $this->session->set($sessionData);
        


        // Update last_login di database
        $this->db->table('refauth')
            ->where('id_auth', $user['id_auth'])
            ->update(['last_login_at' => waktu(), 'last_login_ip' => $this->request->getIPAddress(),'last_user_agent' => $this->request->getUserAgent(), 'active_session_token' => $this->session->getTempdata('device_hash')]);

        // Hapus CAPTCHA dari session setelah berhasil
        $this->session->remove('captcha_code');

        return $this->response->setJSON([
            'csrf_token' => csrf_token(),
            'csrf_hash' => csrf_hash(),
            'success'  => true,
            'lockout' => false,
            'message'  => 'Login berhasil! Mengalihkan...',
            'redirect' => base_url('dashboard'),
            'user'     => [
                'nama' => $user['nama_lengkap'],
                'role' => $user['role'],
            ]
        ]);
    }

    // -------------------------------------------------------
    //  REFRESH CAPTCHA VIA AJAX (opsional: generate dari server)
    // -------------------------------------------------------
    public function refreshCaptcha()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['success' => false]);
        }

        $code = $this->generateCaptchaSession();

        return $this->response->setJSON([
            'success' => true,
            'captcha' => $code
        ]);
    }

    // -------------------------------------------------------
    //  LOGOUT
    // -------------------------------------------------------
    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(base_url());
    }

    // -------------------------------------------------------
    //  HELPER: Generate & Simpan CAPTCHA ke Session
    // -------------------------------------------------------
    private function setDeviceConf(){
        helper('Fungsi_helper');

        if($this->checkLockout()){
            return [
                'device_hash'   => $this->session->getTempdata('device_hash'),
                'ip_address'    => $this->session->getTempdata('ip_address'),
            ];
        }
        
        $data = [
            'device_hash'   =>randomStrings(16),
            'ip_address'    => $this->request->getIPAddress(),
        ];

        $this->session->setTempdata($data, 300);
        return $data;
    }
    
    private function checkLockout(){
        $exists = $this->db->table('device_logs')
                        ->where(['ip_address' => $this->session->getTempdata('ip_address'), 'device_hash' => $this->session->getTempdata('device_hash')])
                        ->countAllResults() > 0;
        return ($exists) ? true : false;
    }
    
    private function generateCaptchaSession(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $code  = '';
        for ($i = 0; $i < 6; $i++) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }

        $this->session->set('captcha_code', $code);
        return strtoupper($code);
    }

    // -------------------------------------------------------
    //  HELPER: Increment Login Attempts
    // -------------------------------------------------------
    private function incrementLoginAttempts(): void
    {
        $attempts = ($this->session->getTempdata('login_attempts') ?? 0) + 1;
        $this->session->setTempdata(['login_attempts'=>$attempts], 120);    
    }


}