<?php

namespace App\Services;

use App\Repositories\UserRepo\IUserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    protected $userRepo;
    public function __construct(IUserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function register(array $data)
    {
        if ($this->userRepo->checkEmail($data["email"])) {
            throw new \Exception('Email đã tồn tại!');
        }
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepo->create($data);
        $token = $this->createToken($user);
        return $token;
    }
    public function login(array $data)
    {
        $user = $this->userRepo->findByEmail($data['email']);
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return ['error' => 'Sai tài khoản hoặc mật khẩu', 'token' => null];
        }
        $remember = $data['remember'] ?? false;
        $token = $this->createToken($user);
        return [
            'token'    => $token,
            'remember' => $remember
        ];
    }
    public function loginGoogle($data)
    {
        $user = $this->userRepo->findByEmail($data['email']);

        if (!$user) {
            $user = $this->userRepo->create([
                'email'    => $data['email'],
                'name'     => $data['name'],
                'password' => bcrypt(\Illuminate\Support\Str::random(16)), // mật khẩu random
                'role'     => 'user',
            ]);
        }
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Không thể tạo user mới'
            ];
        }
        $token = $this->createToken($user);
        return [
            'token'   => $token,
            'success' => true,
            'user'    => $user,
        ];
    }
    public function createToken($user)
    {

        $token = JWTAuth::fromUser($user, []);
        return $token;
    }

    // -----------------------------ADMIN----------------------------
    public function loginAdmin($data)
    {
        $credentials = [
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();

            // Check role
            if (Auth::user()->role === 'admin') {
                return [
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                ];
            } else {
                Auth::logout(); // đăng xuất luôn
                return [
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập',
                ];
            }
        }

        return [
            'success' => false,
            'message' => 'Email hoặc mật khẩu không chính xác',
        ];
    }

    public function registerAdmin($data)
    {
        try {
            $user = $this->userRepo->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => 'admin',
            ]);

            Auth::login($user);

            return [
                'success' => true,
                'message' => 'Đăng ký thành công',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Đăng ký thất bại: ' . $e->getMessage(),
            ];
        }
    }
}
