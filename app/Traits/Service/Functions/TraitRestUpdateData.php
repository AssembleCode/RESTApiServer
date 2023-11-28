<?php

namespace App\Traits\Service\Functions;

trait TraitRestUpdateData
{
    public function updateData($request, $id)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        // REQUEST, RULES, MESSAGES, ATTRIBUTES
        $request->validate($this->validator->rules(), $this->validator->messages(), $this->validator->attributes());
        $this->repository->update($request->all(), $id);
        $response = $this->repository->findById($id);
        return $response;
    }
}
