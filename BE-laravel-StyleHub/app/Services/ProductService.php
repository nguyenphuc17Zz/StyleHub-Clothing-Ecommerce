<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepo\IProductRepository;

class ProductService
{
    protected $productRepo;
    public function __construct(IProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }
    public function findAll($keyword = null)
    {
        return $this->productRepo->findAll($keyword);
    }
    public function findById($id)
    {
        return $this->productRepo->findById($id);
    }
    public function create(array $data)
    {
        $file = $data['thumbnail'];
        $ext = $file->getClientOriginalExtension(); // lấy phần mở rộng
        $filename = uniqid() . '.' . $ext;
        $file->move(public_path('uploads/products'), $filename);
        $data['thumbnail'] = $filename;

        return $this->productRepo->create($data);
    }
    public function update(array $data, $id)
    {
        $product = $this->productRepo->findById($id);
        if (!$product) {
            return false;
        }

        if (isset($data['thumbnail'])) {
            if ($product->thumbnail && file_exists(public_path('uploads/products/' . $product->thumbnail))) {
                unlink(public_path('uploads/products/' . $product->thumbnail));
            }

            $file = $data['thumbnail'];
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            $file->move(public_path('uploads/products'), $filename);
            $product->thumbnail = $filename;
        }

        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];

        return $this->productRepo->update($product);
    }

    public function delete($id)
    {
        $product = $this->productRepo->findById($id);
        if (!$product) {
            return false;
        }
        $path = public_path('uploads/products/' . $product->thumbnail);
        if (file_exists($path)) {
            unlink($path);
        }
        return $this->productRepo->delete($id);
    }
    public function getAll()
    {
        return $this->productRepo->getAll();
    }
    public function getLatest()
    {
        return $this->productRepo->getLatest();
    }
    public function product_Detail($id)
    {
        return $this->productRepo->product_Detail($id);
    }
    public function getAllProduct($category_id, $search, $perPage)
    {
        return $this->productRepo->getAllProduct($category_id, $search, $perPage);
    }
    public function productSearchSuggestion($keyword)
    {
        return $this->productRepo->productSearchSuggestion($keyword);
    }
}
