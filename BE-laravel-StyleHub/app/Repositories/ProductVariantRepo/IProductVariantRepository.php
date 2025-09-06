<?php

namespace App\Repositories\ProductVariantRepo;

interface IProductVariantRepository
{
    public function findAll($keyword = null);
    public function findById($id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}
