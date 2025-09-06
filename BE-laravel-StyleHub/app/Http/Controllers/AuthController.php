<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use App\Services\ResetpasswordService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    protected AuthService $authService;
    protected ResetpasswordService $resetpasswordService;
    public function __construct(AuthService $authService, ResetpasswordService $resetpasswordService)
    {
        $this->authService = $authService;
        $this->resetpasswordService = $resetpasswordService;
    }
    public function login(Request $request)
    {
        $data = $this->authService->login($request->only('email', 'password', 'remember'));
        if (!$data['token']) return response()->json(['error' => 'Can not Authentication'], 401);
        return response()->json([
            'token'      => $data['token'],
            'remember'   => $data['remember']
        ]);
    }
    public function register(Request $request)
    {
        $token = $this->authService->register($request->only('name', 'email', 'password'));
        return response()->json(['token' => $token]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $data = $this->resetpasswordService->forgotPassword($request->only('email'));
        return response()->json($data);
    }
    public function confirmToken(Request $request)
    {
        $request->validate(['email' => 'required|email', 'token' => 'required|string']);
        $data = $this->resetpasswordService->confirmToken($request->only('email', 'token'));
        return response()->json($data);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);
        $data = $this->resetpasswordService->resetPassword($request->only('email', 'password'));
        return response()->json($data);
    }
    public function loginGoogle(Request $request)
    {
        $data = $this->authService->loginGoogle($request->only('email', 'name'));
        return response()->json($data);
    }

    // -----------------------------ADMIN----------------------------

    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Xử lý đăng nhập
    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $data = $this->authService->loginAdmin($request->only('email', 'password'));

        if ($data['success']) {
            return redirect()->intended('/dashboard');
        }

        return back()->with('message', $data['message']);
    }
    // Hiển thị form đăng ký
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    // Xử lý đăng ký
    public function registerAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:1', // password_confirmation tự động kiểm tra
        ]);

        $data = $this->authService->registerAdmin($request->only('name', 'email', 'password'));

        if ($data['success']) {
            return redirect('/login'); // hoặc trang chính
        }

        return back()->withErrors([
            'email' => $data['message'],
        ]);
    }
    public function logout(Request $request)
    {
        Auth::logout();               // Xóa session user
        $request->session()->invalidate();  // Hủy session hiện tại
        $request->session()->regenerateToken(); // Tạo CSRF token mới

        return redirect()->route('login'); // Quay về login
    }
}
