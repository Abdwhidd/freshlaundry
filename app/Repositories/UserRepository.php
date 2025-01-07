<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    // Implementasi untuk mencari pengguna berdasarkan email
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    // Implementasi untuk mencari pengguna berdasarkan id
    public function findById(int $id)
    {
        return User::find($id);
    }
}
