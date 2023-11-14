<?php

namespace App\Repositories;

use App\Models\Examples;

class ExampleRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new Examples();
    }
}
