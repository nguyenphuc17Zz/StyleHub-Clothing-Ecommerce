<?php

namespace App\Services;

use App\Models\PasswordReset;
use App\Repositories\ResetpasswordRepo\IResetpasswordRepository;
use App\Repositories\UserRepo\IUserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class ResetpasswordService
{
    protected $resetpasswordRepo;
    protected $userRepo;
    public function __construct(IResetpasswordRepository $resetpasswordRepo, IUserRepository $userRepo)
    {
        $this->resetpasswordRepo = $resetpasswordRepo;
        $this->userRepo = $userRepo;
    }
    public function forgotPassword($data)
    {
        $user = $this->userRepo->findByEmail($data["email"]);
        if (!$user) {
            return ['message' => 'Email không tồn tại', 'success' => false];
        }
        $token = Str::random(6);
        $this->resetpasswordRepo->updateOrCreate([
            'email' => $data['email'],
            'token' => $token
        ]);


        Mail::raw("Mã khôi phục mật khẩu của bạn là: $token", function ($message) use ($data) {
            $message->to($data['email'])
                ->subject('Khôi phục mật khẩu');
        });
        return [
            'message' => 'Đã gởi token đặt lại mật khẩu qua email của bạn',
            'success' => true
        ];
    }
    public function confirmToken($data)
    {
        $user = $this->resetpasswordRepo->findByEmailAndToken($data['email'], $data['token']);
        if (!$user) {
            return ['message' => 'Token không hợp lệ', 'success' => false];
        } else {
            return ['message' => 'Xác nhận token thành công', 'success' => true];
        }
    }
    public function resetPassword($data)
    {
        $user = $this->userRepo->findByEmail($data['email']);
        if (! $user) {
            return ['message' => '', 'success' => false];
        } else {
            $data['password'] = Hash::make($data['password']);
            try {
                $this->userRepo->updatePassword($data);
                return ['message' => '', 'success' => true];
            } catch (\Exception $e) {
                return ['message' => $e->getMessage(), 'success' => false];
            }
        }
    }
}
