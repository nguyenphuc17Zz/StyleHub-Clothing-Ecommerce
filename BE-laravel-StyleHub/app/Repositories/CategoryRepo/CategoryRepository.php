<?php

namespace App\Repositories\CategoryRepo;

use App\Models\Category;

class CategoryRepository implements ICategoryRepository
{
    public function findAll($keyword = null)
    {
        $query = Category::query();
        if ($keyword) {
            $query->where('name', 'like', "%$keyword%");
        }
        $perPage = 10;
        return $query->paginate($perPage);
    }

    public function findById($id)
    {
        return Category::find($id);
    }
    public function update(array $data, $id)
    {
        $category = Category::find($id);
        $category->name = $data["name"];
        $category->save();
        return $category;
    }
    public function delete($id)
    {
        $category = Category::find($id);
        if ($category->products()->count() > 0) {
            return false;
        }
        $category->delete();
        return true;
    }
    public function create(array $data)
    {
        $category = Category::create($data);
        $category->save();
        return $category;
    }
    public function getAll()
    {
        return Category::all();
    }
}
