<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $categories = $this->categoryService->findAll($keyword);
        return view("categories.index", compact("categories", "keyword"));
    }
    public function create()
    {
        return view("categories/create");
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);
        $this->categoryService->create($request->only('name'));
        return redirect()->route('categories.index')->with('success', 'Thêm danh mục thành công!');
    }
    public function edit($id)
    {
        $category = $this->categoryService->findById($id);

        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
        ]);


        $this->categoryService->update($id, $request->only('name'));

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật danh mục thành công!');
    }
    public function destroy($id)
    {
        $flag = $this->categoryService->delete($id);
        if ($flag) {
            return redirect()->route('categories.index')
                ->with('success', 'Xóa danh mục thành công!');
        } else {
            return redirect()->route('categories.index')
                ->with('error', 'Không thể xóa danh mục vì vẫn còn sản phẩm.');
        }
    }

    // --------------------------------API------------------------------------
    public function getAllCategories()
    {
        $categories = $this->categoryService->getAll();
        return response()->json([
            'success' => true,
            'categories' => $categories
        ]);
    }
}
