<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function createToken($data) {
    $key = getenv('JWT_SECRET') ?: '$2y$10$OJe/7iJbtMwfBMjqtntTSuWPBFCJ4HRSxphV4RoYhk3ECNS2hZUa.';
    $payload = [
        'iss' => 'edusmara-api',
        'iat' => time(),
        'exp' => time() + 3600, // Token expired 1 jam
        'data' => $data
    ];
    return JWT::encode($payload, $key, 'HS256');
}

function validateToken($token) {
    try {
        $key = getenv('JWT_SECRET') ?: '$2y$10$OJe/7iJbtMwfBMjqtntTSuWPBFCJ4HRSxphV4RoYhk3ECNS2hZUa.';
        return JWT::decode($token, new Key($key, 'HS256'));
    } catch (\Exception $e) {
        return false;
    }
} 