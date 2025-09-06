<?php

namespace App\Repositories\ResetpasswordRepo;

use App\Models\PasswordReset;

class ResetpasswordRepository implements IResetpasswordRepository
{
    public function updateOrCreate(array $data)
    {
        return PasswordReset::updateOrCreate(
            ['email' => $data['email']],
            ['token' => $data['token']]
        );
    }
    public function findByEmailAndToken($email, $token)
    {
        return PasswordReset::where('email', $email)
            ->where('token', $token)
            ->first(); 
    }
    
}
