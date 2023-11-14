<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getModel();
    public function newQuery();
    public function all();
    public function findById($id, array $columns = ['*']);
    public function firstWhere($column, $value);
    public function create(array $data);
}
