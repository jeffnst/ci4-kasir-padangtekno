<?php

namespace App\Controllers\Api;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format    = 'json';

    // GET: Ambil semua data user
    public function index()
    {
        $users = $this->model->findAll();
        return $this->respond($users);
    }

    // GET: Ambil satu user berdasarkan ID
    public function show($id = null)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }
        return $this->respond($user);
    }

    // POST: Tambah user baru
    public function create()
    {
        $rules = [
            'name'     => 'required|min_length[3]|max_length[100]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'role'     => 'required|in_list[admin,kasir,owner]',
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $json = $this->request->getJSON();

        $data = [
            'name'     => $json->name,
            'email'    => $json->email,
            'password' => password_hash($json->password, PASSWORD_BCRYPT),
            'role'     => $json->role,
        ];

        $this->model->save($data);
        return $this->respondCreated(['message' => 'User berhasil ditambahkan']);
    }

    // PUT/PATCH: Update data user
    public function update($id = null)
    {
        // Cari user berdasarkan ID
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        // Ambil data dari JSON
        $data = $this->request->getJSON(true); // Gunakan `true` untuk mengembalikan array

        // Validasi dinamis berdasarkan field yang dikirim
        $rules = [];
        if (isset($data['name'])) {
            $rules['name'] = 'min_length[3]|max_length[100]';
        }
        if (isset($data['email'])) {
            $rules['email'] = 'valid_email|is_unique[users.email,id,' . $id . ']';
        }
        if (isset($data['password'])) {
            $rules['password'] = 'min_length[6]';
        }
        if (isset($data['role'])) {
            $rules['role'] = 'in_list[admin,kasir,owner]';
        }

        // Jalankan validasi hanya jika ada rules
        if (!empty($rules) && !$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Hash password jika ada
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        // Tambahkan updated_at
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Update data
        $this->model->update($id, $data);

        // Berikan response sukses
        return $this->respond(['message' => 'User berhasil diperbarui']);
    }

    // DELETE: Soft delete user
    public function delete($id = null)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->failNotFound('User tidak ditemukan');
        }

        $this->model->delete($id);
        return $this->respondDeleted(['message' => 'User berhasil dihapus (soft delete)']);
    }

    // Restore: Mengembalikan user yang sudah dihapus (soft delete)
    public function restore($id = null)
    {
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update(['deleted_at' => null]);

        return $this->respond(['message' => 'User berhasil dikembalikan']);
    }
}
