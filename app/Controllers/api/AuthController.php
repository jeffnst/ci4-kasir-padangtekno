<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class AuthController extends ResourceController
{

    public function login()
    {
        $model = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $user = $model->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password'])) {
            return $this->failUnauthorized('Email atau password salah');
        }
        $key = getenv('JWT_SECRET_KEY');
        $payload = [
            'sub' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // Token berlaku 1 hari

        ];
        $token = JWT::encode($payload, $key, 'HS256');
        return $this->respond(['token' => $token]);
    }
    public function index()
    {
        //
    }
}
