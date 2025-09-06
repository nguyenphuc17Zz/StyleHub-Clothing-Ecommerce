<?php

namespace App\Http\Controllers;

use App\Services\User;
use App\Services\UserService;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    //
    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function index(Request $request)
    {
        $keyword = $request->get(key: 'search');

        $users = $this->userService->findAll($keyword);
        return view("users.index", compact("users", "keyword"));
    }
    public function create()
    {
        return view("users.create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email',
            'password' => 'required|string|min:1',
            'role'     => 'required|in:admin,user',
        ]);

        $result = $this->userService->create($request->all());

        if (!$result) {
            return redirect()->back()->withInput()
                ->withErrors(['email' => 'Email đã tồn tại!']);
        }

        return redirect()->route('users.index')
            ->with('success', 'Thêm user thành công!');
    }

    public function edit($id)
    {
        $user = $this->userService->findById($id);
        return view("users.edit", compact("user"));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'email' => 'required|string',
            'password'       => 'nullable|string|min:1',
            'role' => 'required|in:admin,user',
        ]);
        $request['id'] = $id;
        $this->userService->update($request->all());
        return redirect()->route('users.index')
            ->with('success', 'Update user thành công!');
    }
    public function destroy($id)
    {
        $check = $this->userService->delete($id);
        if (!$check) {
            return redirect()->route('users.index')
                ->with('error', 'Xóa user thất bại!');
        } else {
            return redirect()->route('users.index')
                ->with('success', 'Xóa user thành công!');
        }
    }

    // -----------------------------------API -----------------------
    public function profileApi(Request $request)
    {
        $user = $request->user();
        $userData = $this->userService->findById($user->id);
        return response()->json($userData);
    }

    public function updateProfileAPI(Request $request)
    {
       
        $request->validate([
            'name'        => 'required|string|max:255',
            'password'       => 'nullable|string|min:1',
        ]);
        $user = $request->user();
        $data = $request->only(['name', 'password']);
        $data['id'] = $user->id;

        $updated = $this->userService->updateProfileApi($data);

        if ($updated) {
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thành công',
                'user'    => $this->userService->findById($user->id)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Cập nhật thất bại'
        ], 500);
    }
}
