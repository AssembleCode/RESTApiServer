<?php

namespace App\Traits\Service\Functions;

trait TraitRestDeleteData
{
    public function deleteData($id)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }

        $entity = $this->repository->findById($id);
        if (!$entity) {
            return $this->notFoundResponse();
        }
        $response = $entity->delete($id);
        if (!$response) {
            return $this->errorResponse();
        }
        return $this->deleteResponse();
    }
}
