<?php

namespace App\Repositories\ImageRepo;

interface IImageRepository
{
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
    public function findById($id);
    public function findAll($keyword=null);
}
