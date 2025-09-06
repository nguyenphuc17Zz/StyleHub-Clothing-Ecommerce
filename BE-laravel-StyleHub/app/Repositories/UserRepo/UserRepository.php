<?php

namespace App\Repositories\UserRepo;

use App\Models\User;

class UserRepository implements IUserRepository
{
    public function findById($id)
    {
        return User::find($id);
    }
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    public function create(array $data)
    {
        return User::create($data);
    }
    public function updatePassword($data)
    {
        return User::where('email', $data['email'])->update(['password' => $data['password']]);
    }
    public function checkEmail($email)
    {
        return User::where('email', $email)->exists();
    }
    public function findAll($keyword = null)
    {
        $query = User::query();

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }

        $perPage = 10;
        return $query->paginate($perPage);
    }

    public function update($data)
    {
        $user = User::find($data['id']);
        $user->update($data);
    }
    public function delete($id)
    {
        return User::destroy($id);
    }
}
