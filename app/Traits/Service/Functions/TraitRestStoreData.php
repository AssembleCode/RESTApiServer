<?php

namespace App\Traits\Service\Functions;

trait TraitRestStoreData
{
    public function storeData($request)
    {
        if (!isset($this->repository)) {
            return $this->errorResponse('Repository not found');
        }
        // REQUEST, RULES, MESSAGES, ATTRIBUTES
        // Validator::make($request->all(), $this->validator->rules(), $this->validator->messages(), $this->validator->attributes())->validate();
        $request->validate($this->validator->rules(), $this->validator->messages(), $this->validator->attributes());
        $storedData = $this->repository->create($request->all());
        return $storedData;
    }
}
