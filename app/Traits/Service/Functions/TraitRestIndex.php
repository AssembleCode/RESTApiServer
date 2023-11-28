<?php

namespace App\Traits\Service\Functions;

trait TraitRestIndex
{
    public function index()
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        $response = $this->repository->all();
        return $response;
    }
}
