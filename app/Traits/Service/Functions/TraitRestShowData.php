<?php

namespace App\Traits\Service\Functions;

trait TraitRestShowData
{
    public function showData($id)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        $response = $this->repository->findById($id);
        return $response;
    }
}
