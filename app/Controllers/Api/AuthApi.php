<?php
namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;

class AuthApi extends ResourceController
{
    use ResponseTrait;

    public function login()
    {
        $rules = [
            'username'  => 'required',
            'ID_produc' => 'required',
            'password'  => 'required'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors(['status' => 400, 'errors' => $this->validator->getErrors()], 400);
        }

        $username  = $this->request->getPost('username');
        $id_produc = $this->request->getPost('ID_produc');
        $password  = $this->request->getPost('password');

        $db = \Config\Database::connect('apiedusmara');
        $user = $db->table('userslicenseapp')
                   ->where('username', $username)
                   ->where('produc_key', $id_produc)
                   ->get()
                   ->getRowArray();

        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Kredensial tidak valid');
        }

        helper('jwt');
        $token = createToken([
            'username'  => $user['username'],
            'produc_key' => $user['produc_key']
        ]);

        return $this->respond([
            'status'  => 200,
            'message' => 'Login berhasil',
            'token'   => $token
        ]);
    }
}