<?php

namespace App\Services;

use App\Repositories\CategoryRepo\CategoryRepository;
use App\Repositories\CategoryRepo\ICategoryRepository;

class CategoryService
{
    protected $categoryRepo;
    public function __construct(ICategoryRepository $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function create(array $data)
    {
        $category = $this->categoryRepo->create($data);
        return $category;
    }
    public function update(int $id, array $data)
    {
        $category = $this->categoryRepo->update($data, $id);
        return $category;
    }
    public function delete(int $id)
    {
        return $this->categoryRepo->delete($id);
    }
    public function findById(int $id)
    {
        $category = $this->categoryRepo->findById($id);
        return $category;
    }
    public function findAll($keyword=null)
    {
        $categories = $this->categoryRepo->findAll($keyword);
        return $categories;
    }
    public function getAll(){
        return $this->categoryRepo->getAll();
    }
}
