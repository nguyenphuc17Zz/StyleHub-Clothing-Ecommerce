<?php

namespace App\Repositories\ChatRepo;

interface IChatRepository {
    public function getAll($keyword);
    public function sendMessages($id);
}
