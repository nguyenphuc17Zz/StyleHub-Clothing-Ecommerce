<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    //
    protected $imageService;
    protected $productService;
    public function __construct(ImageService $imageService, ProductService $productService)
    {
        $this->imageService = $imageService;
        $this->productService = $productService;
    }
    public function index(Request $request)
    {
        $keyword = $request->get(key: 'search');
        $images = $this->imageService->findAll($keyword);
        return view("images.index", compact("images", "keyword"));
    }
    public function create()
    {
        $products = $this->productService->getAll();
        return view("images.create", compact("products"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image'   => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);
        $this->imageService->create($request->all());
        return redirect()->route('images.index')
            ->with('success', 'Thêm ảnh thành công!');
    }
    public function edit($id)
    {
        $image = $this->imageService->findById($id);
        $products = $this->productService->getAll();

        return view('images.edit', compact('image', 'products'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'image'   => 'required|image|mimes:jpg,jpeg,png,gif|max:2048',

        ]);
        $request['id'] = $id;
        $this->imageService->update($request->all());
        return redirect()->route('images.index')
            ->with('success', 'Sửa ảnh thành công!');
    }
    public function destroy($id)
    {
        $this->imageService->delete($id);
        return redirect()->route('images.index')
            ->with('success', 'Xóa ảnh thành công!');
    }
}
