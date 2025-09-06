<?php

namespace App\Services;

use App\Repositories\UserRepo\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $userRepo;
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function create(array $data)
    {
        if ($this->userRepo->checkEmail($data["email"])) {
            return false;
        }
        $data["password"] = Hash::make($data['password']);
        $this->userRepo->create($data);
        return true;
    }
    public function update($data)
    {
        $user = $this->userRepo->findById($data['id']);

        if (isset($data['email']) && $data['email'] !== $user->email) {
            if (!$this->userRepo->checkEmail($data['email'])) {
                return false;
            }
        }

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->userRepo->update($data);
        return true;
    }

    public function delete($id)
    {
        $deleteId = $id;
        $currentUserId = Auth::id();

        if ($deleteId == $currentUserId) {
            return false;
        }
        return $this->userRepo->delete($id);
    }
    public function findById($id)
    {
        return $this->userRepo->findById($id);
    }
    public function findAll($keyword = null)
    {
        return $this->userRepo->findAll($keyword);
    }
    // -----------------------------------API -----------------------
    public function updateProfileApi($data)
    {
        $user = $this->userRepo->findById($data['id']);
        if (!$user) {
            return false;
        }

        $user->name = $data['name'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        return $user->save();
    }
}
