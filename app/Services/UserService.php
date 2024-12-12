<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function searchUser($query)
    {
        $users = User::where('name', 'like', '%' . $query . '%')
            ->orWhere('role', 'like', '%' . $query . '%')
            ->get();
        $users = User::hydrate($users->toArray());
        return $users;
    }
    public function getAllUsers()
    {
        return User::all();
    }

    public function getUserById($id)
    {
        return User::findOrFail($id);
    }

    public function createUser($data)
    {
        return User::create($data);
    }

    public function updateUser($id, $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $user;
    }
}
