<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function init()
    {
    }

    public function getModel()
    {
        return $this->model;
    }

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function firstWhere($column, $value)
    {
        return $this->model->firstWhere("{$column}", $value);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }
}
