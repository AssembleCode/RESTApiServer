<?php

namespace App\Services;

use App\Repositories\ItemRepository;
use App\Traits\Service\RestServiceTrait;
use App\Validators\ItemValidator;

class ItemService
{
    private $repository;
    private $validator;
    private $partialUpdateFields = ['status'];

    use RestServiceTrait;

    public function __construct(ItemRepository $repository, ItemValidator $validator)
    {
        $this->repository = $repository;
        $this->validator = $validator;
    }

}
