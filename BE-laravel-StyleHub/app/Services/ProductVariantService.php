<?php

namespace App\Services;

use App\Repositories\ProductVariantRepo\IProductVariantRepository;

class ProductVariantService
{
    protected $productVariantRepo;

    public function __construct(IProductVariantRepository $productVariantRepo)
    {
        $this->productVariantRepo = $productVariantRepo;
    }


    public function findAll($keyword = null)
    {
        return $this->productVariantRepo->findAll($keyword);
    }


    public function findById($id)
    {
        return $this->productVariantRepo->findById($id);
    }


    public function create(array $data)
    {
        return $this->productVariantRepo->create($data);
    }


    public function update(array $data)
    {
        return $this->productVariantRepo->update($data);
    }


    public function delete($id)
    {
        return $this->productVariantRepo->delete($id);
    }
}
