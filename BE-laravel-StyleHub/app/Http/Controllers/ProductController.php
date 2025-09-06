<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{
    //
    protected $productService;
    protected $categoryService;
    public function __construct(ProductService $productService, CategoryService $categoryService)
    {
        $this->productService = $productService;
        $this->categoryService = $categoryService;
    }
    public function index(Request $request)
    {
        $keyword = $request->get(key: 'search');
        $products = $this->productService->findAll($keyword);
        return view("products.index", compact("products", "keyword"));
    }
    public function create()
    {
        $categories = $this->categoryService->findAll();
        return view("products.create", compact("categories"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $this->productService->create($request->all());

        return redirect()->route('products.index')
            ->with('success', 'Thêm sản phẩm thành công!');
    }
    public function edit($id)
    {
        $product = $this->productService->findById($id);
        $categories = $this->categoryService->findAll();

        return view('products.edit', compact('product', 'categories'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'required|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $this->productService->update($request->all(), $id);
        return redirect()->route('products.index')
            ->with('success', 'Sửa sản phẩm thành công!');
    }
    public function destroy($id)
    {
        $check = $this->productService->delete($id);
        if ($check) {
            return redirect()->route('products.index')
                ->with('success', 'Xóa sản phẩm thành công!');
        }
        return redirect()->route('products.index')
            ->with('error', 'Xóa sản phẩm thất bại!');
    }
    //----------------------------API-------------------------
    public function getLatest()
    {
        $products = $this->productService->getLatest();
        return response()->json([
            'success' => true,
            'products_latest' => $products
        ]);
    }
    public function product_Detail($id)
    {
        return $this->productService->product_Detail($id);
    }
    public function getAllProduct(Request $request)
    {
        $category_id = $request->get('category_id', 'all');
        $search = $request->get('search', '');
        $perPage = 9;

        $products = $this->productService->getAllProduct($category_id, $search, $perPage);

        return response()->json([
            'success' => true,
            'products' => $products
        ]);
    }
    public function productSearchSuggestion(Request $request)
    {
        $keyword = $request->query('keyword');
        Log::info($keyword);
        $products = $this->productService->productSearchSuggestion($keyword);
        Log::info($keyword);
        return response()->json([
            "success" => true,
            "products" => $products
        ]);
    }
    public function sizeSuggest(Request $request)
    {
        $weight = $request->query("weight");
        $age = $request->query("age");
        $height = $request->query("height");

        $script = "D:\\model predict size\\predict.py";

        $weight = escapeshellarg($weight);
        $age = escapeshellarg($age);
        $height = escapeshellarg($height);

        $command = "python " . escapeshellarg($script) . " $weight $age $height";
        $size = shell_exec($command);

        return response()->json([
            "success" => true,
            "size" => trim($size),
        ]);
    }
}
