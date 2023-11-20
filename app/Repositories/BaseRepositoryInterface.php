<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function newQuery();
    public function all();
    public function findById($id, array $columns = ['*']);
    public function firstWhere($column, $value);
    public function create(array $data);
    public function update(array $data, $id);
}
