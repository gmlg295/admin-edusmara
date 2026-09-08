<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $header = $request->getHeaderLine('Authorization');
        
        if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
            return service('response')->setJSON([
                'status' => 401,
                'message' => 'Token tidak ditemukan'
            ])->setStatusCode(401);
        }

        $token = $matches[1];
        $key = getenv('JWT_SECRET') ?: '$2y$10$OJe/7iJbtMwfBMjqtntTSuWPBFCJ4HRSxphV4RoYhk3ECNS2hZUa.';

        try {
            // Decode token menggunakan Key object (wajib di firebase/php-jwt v6+)
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            
            // Masukkan data user ke request agar bisa dipakai di Controller
            $request->user = $decoded->data;

        } catch (\Exception $e) {
            return service('response')->setJSON([
                'status' => 401,
                'message' => 'Token tidak valid: ' . $e->getMessage() // Tampilkan pesan error asli dulu untuk debug
            ])->setStatusCode(401);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}