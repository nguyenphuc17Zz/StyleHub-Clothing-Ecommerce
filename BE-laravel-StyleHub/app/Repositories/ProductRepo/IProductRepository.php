<?php

namespace App\Repositories\ProductRepo;

interface IProductRepository
{
    public function create(array $data);
    public function findById($id);
    public function findAll($keyword = null);
    public function update(array $data);
    public function delete($id);
    public function getAll();
    public function getLatest();
    public function product_Detail($id);
    public function relatedProduct($id);
    public function getAllProduct($category_id,  $search, $perPage);
    public function productSearchSuggestion($search);
}
