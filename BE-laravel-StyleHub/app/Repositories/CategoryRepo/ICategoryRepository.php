<?php

namespace App\Repositories\CategoryRepo;

interface ICategoryRepository
{
    public function create(array $data);
    public function findById($id);
    public function findAll();
    public function update(array $data, $id);
    public function delete($id);
    public function getAll();
}
