<?php

namespace App\Services;

use App\Repositories\ImageRepo\IImageRepository;

class ImageService
{
    protected $imageRepo;
    public function __construct(IImageRepository $imageRepo)
    {
        $this->imageRepo = $imageRepo;
    }
    public function create(array $data)
    {
        $file = $data['image'];
        $ext = $file->getClientOriginalExtension();
        $filename = uniqid() . '.' . $ext;
        $file->move(public_path('uploads/images'), $filename);
        $data['url'] = $filename;
        $this->imageRepo->create($data);
    }
    public function update(array $data)
    {
        $image = $this->imageRepo->findById($data['id']);
        if (isset($data["image"])) {
            if (file_exists(public_path('uploads/images/' . $image->url))) {
                unlink(public_path('uploads/images/' . $image->url));
            }
            $file = $data['image'];
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid() . '.' . $ext;
            $file->move(public_path('uploads/images'), $filename);
            $data['url'] = $filename;
        }
        $image = $this->imageRepo->update($data);
    }
    public function delete($id)
    {
        $image = $this->imageRepo->findById($id);
        $path = public_path('uploads/images/' . $image->url);
        if (file_exists($path)) {
            unlink($path);
        }
        $this->imageRepo->delete($id);
    }
    public function findById($id)
    {
        return $this->imageRepo->findById($id);
    }
    public function findAll($keyword = null)
    {
        return $this->imageRepo->findAll($keyword);
    }
}
