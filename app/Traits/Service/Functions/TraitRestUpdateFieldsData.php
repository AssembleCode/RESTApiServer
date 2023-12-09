<?php

namespace App\Traits\Service\Functions;

trait TraitRestUpdateFieldsData
{
    public function updateFieldsData($request, $id)
    {
        if (!isset($this->repository)) {
            $this->errorResponse('Repository not defined');
        }

        $partialUpdateFields = isset($this->partialUpdateFields) ?  $request->only($this->partialUpdateFields) : [];

        $rules = $this->validator->rules();
        $updatedRules = [];
        foreach ($partialUpdateFields as $key => $value) {
            if (isset($rules[$key])) {
                $updatedRules[$key] = $rules[$key];
            }
        }

        if (isset($this->validator)) {
            $request->validate($updatedRules, $this->validator->messages(), $this->validator->attributes());
        }

        $this->repository->update($partialUpdateFields, $id);
        $response = $this->repository->findById($id);

        return $response;
    }
}
