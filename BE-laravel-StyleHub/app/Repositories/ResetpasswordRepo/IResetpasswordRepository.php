<?php

namespace App\Repositories\ResetpasswordRepo;

interface IResetpasswordRepository
{
    public function updateOrCreate(array $data);
    public function findByEmailAndToken($email, $token);
}
