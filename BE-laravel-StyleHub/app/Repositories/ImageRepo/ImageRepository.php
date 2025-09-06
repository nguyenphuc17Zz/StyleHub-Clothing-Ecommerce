<?php

namespace App\Repositories\ImageRepo;

use App\Models\ProductImage;

class ImageRepository implements IImageRepository
{
    public function create(array $data)
    {
        $image = new ProductImage();
        $image->product_id = $data["product_id"];
        $image->url = $data["url"];
        $image->save();
    }
    public function update(array $data)
    {
        $image = ProductImage::find($data["id"]);
        $image->product_id = $data['product_id'] ?? $image->product_id;
        $image->url = $data['url'];
        $image->save();
        return $image;
    }
    public function delete($id)
    {
        return ProductImage::destroy($id);
    }
    public function findById($id)
    {
        return ProductImage::with("product")->find($id);
    }
    public function findAll($keyword = null)
    {
        $perPage = 10;
        $query = ProductImage::with('product');

        if ($keyword) {
            $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        return $query->paginate($perPage);
    }
}
