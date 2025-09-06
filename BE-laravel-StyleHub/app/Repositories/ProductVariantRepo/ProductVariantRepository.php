<?php

namespace App\Repositories\ProductVariantRepo;

use App\Models\ProductVariant;
use App\Repositories\ProductVariantRepo\IProductVariantRepository;

class ProductVariantRepository implements IProductVariantRepository
{

    public function findAll($keyword = null)
    {
        $perPage = 10;
        $query = ProductVariant::with('product');

        if ($keyword) {
            $query->whereHas('product', function ($q) use ($keyword) {
                $q->where('name', 'like', '%' . $keyword . '%');
            });
        }

        return $query->paginate($perPage);
    }


    public function findById($id)
    {
        return ProductVariant::with('product')->find($id);
    }


    public function create(array $data)
    {
        $variant = new ProductVariant();
        $variant->product_id = $data['product_id'];
        $variant->size       = $data['size'] ?? null;
        $variant->color      = $data['color'];
        $variant->stock      = $data['stock'] ?? 0;
        $variant->save();

        return $variant;
    }


    public function update(array $data)
    {
        $variant = ProductVariant::find($data['id']);
        if (!$variant) {
            return null;
        }

        $variant->product_id = $data['product_id'] ?? $variant->product_id;
        $variant->size       = $data['size'] ?? $variant->size;
        $variant->color      = $data['color'] ?? $variant->color;
        $variant->stock      = $data['stock'] ?? $variant->stock;
        $variant->save();

        return $variant;
    }


    public function delete($id)
    {
        $variant = ProductVariant::find($id);
        if ($variant->orderItems()->count() > 0) {
            return false;
        }

        $variant->delete();
        return true;
    }
}
