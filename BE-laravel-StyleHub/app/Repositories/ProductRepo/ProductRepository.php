<?php

namespace App\Repositories\ProductRepo;

use App\Models\Product;

class ProductRepository implements IProductRepository
{
    public function findAll($keyword = null)
    {
        $query = Product::with('category');
        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
        }
        $perPage = 10;
        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return Product::with('category')->find($id);
    }

    public function create(array $data)
    {
        $product = new Product();
        $product->name = $data['name'];
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->category_id = $data['category_id'];
        $product->thumbnail = $data['thumbnail'] ?? null;
        $product->save();
        return $product;
    }

    public function update($product)
    {

        $product->save();

        return $product;
    }

    public function delete($id)
    {
        $product = Product::find($id);
        if ($product->variants()->count() > 0 || $product->images()->count() > 0) {
            return false;
        }
        $product->delete();
        return true;
    }
    public function getAll()
    {
        return Product::all();
    }
    public function getLatest()
    {
        $limit = 10;
        return Product::with('category')
            ->orderBy('created_at', 'desc')
            ->take($limit)
            ->get();
    }
    public function product_Detail($id)
    {
        $product = Product::with('variants', 'category', 'images')->findOrFail($id);

        $variantsBySize = $product->variants->groupBy('size')->map(function ($variants) {
            return $variants->map(function ($v) {
                return [
                    'id' => $v->id,
                    'color' => $v->color,
                    'stock' => $v->stock,
                ];
            })->values();
        });
        $category_id = $product->category_id;
        $relatedProduct = $this->relatedProduct($category_id);
        // // Lấy danh sách url hình ảnh
        // $images = $product->images->map(function ($img) {
        //     return $img->url;
        // });

        return response()->json([
            'related' => $relatedProduct,
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'price' => $product->price,
                'thumbnail' => $product->thumbnail,
                'category' => $product->category,
                'variants' => $variantsBySize,
                'images' => $product->images,
            ]
        ]);
    }
    public function relatedProduct($id)
    {
        $product = Product::findOrFail($id);

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(10) // lấy tối đa 10 sản phẩm liên quan
            ->get();

        return $related;
    }
    public function getAllProduct($category_id, $search, $perPage = 9)
    {
        $query = Product::query();

        if ($category_id !== 'all') {
            $query->where('category_id', intval($category_id));
        }

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        return $query->paginate($perPage);
    }
    public function productSearchSuggestion($keyword)
    {

        $products = Product::where('name', 'like', "%{$keyword}%")->limit(10)->get();
        return $products;
    }
}
