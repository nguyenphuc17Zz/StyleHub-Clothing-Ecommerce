<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use App\Services\ProductVariantService;
use Illuminate\Http\Request;

class ProductVariantController extends Controller
{
    //
    protected $productService;
    protected $productVariantService;
    public function __construct(ProductService $productService, ProductVariantService $productVariantService)
    {
        $this->productService = $productService;
        $this->productVariantService = $productVariantService;
    }
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $variants = $this->productVariantService->findAll($keyword);
        return view("variants.index", compact("variants", "keyword"));
    }
    public function create()
    {
        $products = $this->productService->getAll();
        return view("variants.create", compact("products"));
    }
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'required|in:S,M,L,XL,XXL',
            'color'      => 'required|string|max:255',
            'stock'      => 'required|integer|min:0',
        ]);
        $this->productVariantService->create($request->only('product_id', 'size', 'color', 'stock'));
        return redirect()->route('variants.index')
            ->with('success', 'Thêm Variant thành công!');
    }
    public function edit($id)
    {
        $variant = $this->productVariantService->findById($id);
        $products = $this->productService->getAll();
        return view("variants.edit", compact("products", "variant"));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size'       => 'required|in:S,M,L,XL,XXL',
            'color'      => 'required|string|max:255',
            'stock'      => 'required|integer|min:0',
        ]);
        $request['id'] = $id;
        $this->productVariantService->update($request->only('product_id', 'size', 'color', 'stock', 'id'));
        return redirect()->route('variants.index')
            ->with('success', 'Update Variant thành công!');
    }
    public function destroy($id)
    {
        $check = $this->productVariantService->delete($id);
        if (!$check) {
            return redirect()->route('variants.index')
                ->with('error', 'Xóa Variant thất bại!');
        } else {
            return redirect()->route('variants.index')
                ->with('success', 'Xóa Variant thành công!');
        }
    }
}
