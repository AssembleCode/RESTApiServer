<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function getModel();
    public function newQuery();
    public function all();
}
