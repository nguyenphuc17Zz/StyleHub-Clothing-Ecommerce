<?php

namespace App\Repositories\UserRepo;

interface IUserRepository
{
    public function findById($id);
    public function findByEmail($email);
    public function create(array $data);
    public function updatePassword($data);
    public function checkEmail($email);
    public function update($data);
    public function delete($id);
    public function findAll($keyword = null);
}
