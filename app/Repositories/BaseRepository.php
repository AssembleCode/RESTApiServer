<?php

namespace App\Repositories;

use App\Services\DebugService;
use App\Services\SessionService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;
    protected $oDataService;
    protected $fieldSearchable;
    protected $orgFilterFields = ['organization_id', 'organogram_id'];

    protected function init() {}

    public function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    public function all()
    {
        return $this->model->all();
    }

    public function list()
    {
        $query = $this->listQuery();
        return $this->applyPaginate($query);
    }

    public function listQuery()
    {
        $this->init();
        $queryParams = $this->oDataService->getQueryParams();
        $oDataParams = $this->oDataService->getODataParams();

        $select     = isset($oDataParams['select'])     ? $oDataParams['select']      : [];
        $compute    = isset($oDataParams['compute'])    ? $oDataParams['compute']     : null;
        $search     = isset($oDataParams['search'])     ? $oDataParams['search']      : '';
        $count      = isset($oDataParams['count'])      ? $oDataParams['count']       : null;
        $expand     = isset($oDataParams['expand'])     ? $oDataParams['expand']      : null;
        $filter     = isset($oDataParams['filter'])     ? $oDataParams['filter']      : [];
        $orderBy    = isset($oDataParams['orderby'])    ? $oDataParams['orderby']     : [];
        $apply      = isset($oDataParams['apply'])      ? $oDataParams['apply']       : [];
        $skip       = isset($oDataParams['skip'])       ? $oDataParams['skip']        : null;
        $top        = isset($oDataParams['top'])        ? $oDataParams['top']         : null;

        $query = $this->newQuery();
        $query = $this->applySelect($query, $select);
        $query = $this->applySearch($query, $search);
        $query = $this->applyFilters($query, $filter);
        $query = $this->applyFilterQueryParams($query, $queryParams);
        // $query = $this->applyOrgFilters($query, $filter);
        $query = $this->applyAggregates($query, $apply);
        $query = $this->applyOrderBy($query, $orderBy);

        return $query;
    }

    public function applyPaginate(Builder $query)
    {
        $this->init();
        $oDataParams = $this->oDataService->getODataParams();

        $skip       = isset($oDataParams['skip'])       ? $oDataParams['skip']        : null;
        $top        = isset($oDataParams['top'])        ? $oDataParams['top']         : null;

        $totalCount     = $this->getTotal($query);
        $perPage        = isset($top)  ? $top : $totalCount;
        $pageCount      = isset($top)  ? ceil($totalCount / $perPage) : 1;
        $currentPage    = isset($skip) ? ceil($skip / $perPage) : 1;

        if (!empty($skip)) {
            $query = $query->skip($skip);
        }
        if (!empty($top)) {
            $query = $query->take($top);
        }

        //Debug Info
        $sessionService = (new SessionService())->init();
        $queryString = DebugService::getSqlWithBindings($query);
        $debug = [
            // 'userId'          => $sessionService->getUserId(),
            // 'organizationId'  => $sessionService->getOrganizationId(),
            // 'organizationIds' => $sessionService->getOrganizationIds(),
            // 'organogramId'    => $sessionService->getOrganogramId(),
            // 'organogramIds'   => $sessionService->getOrganogramIds(),
            // 'queryString'     => $queryString,
            // 'userToken'       => $sessionService->getUserToken(),
            // 'authServerToken' => $sessionService->getAuthServerToken(),
        ];

        return [
            'meta' => [
                'totalCount'  => $totalCount,
                'pageCount'   => $pageCount,
                'currentPage' => $currentPage,
                'perPage'     => $perPage,
                'summary'     => [],
                'debug'       => $debug,
            ],
            'results' => $query->get(),
        ];
    }

    protected function applySelect(Builder $query, array $fields): Builder
    {
        if (empty($fields)) {
            return $query;
        }

        return $this->model->select($fields);
    }

    protected function applySearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        if (count($this->fieldSearchable)) {
            $query->where(function (Builder $query) use ($search) {
                foreach ($this->fieldSearchable as $key) {
                    $query->orWhere($key, 'like', "%{$search}%");
                }
            });
        }

        return $query;
    }

    protected function applyFilters(Builder $query, array $filters = [], $prefix = 'db_prefix'): Builder
    {
        if (empty($filters)) {
            return $query;
        }

        $rawQuery = '';
        foreach ($filters as $filter) {
            if ($filter == 'and') {
                $rawQuery .= ' AND ';
            } else if ($filter == 'or') {
                $rawQuery .= ' OR ';
            } else {
                $rawQuery .= $filter['var1'] . " " . $filter['op'] . " " . $filter['var2'];
            }
        }

        $rawQuery = $this->replaceDbPrefix($rawQuery, $prefix);
        $query = $query->whereRaw($rawQuery);

        return $query;
    }

    protected function applyFilterQueryParams(Builder $query, array $queryParams = [])
    {
        return $query;
    }

    // protected function applyOrgFilters(Builder $query, array $filters = []): Builder
    // {
    //     $sessionService = (new SessionService())->init();

    //     $userGroupCodeList = $sessionService->getUserGroupCodeList();
    //     if (!empty($userGroupCodeList) && in_array(config('app.group_super_admin_code'), $userGroupCodeList)) {
    //         return $query;
    //     }

    //     $organizationIds = $sessionService->getAllUserAssignedOrganizationIds();
    //     $organogramIds = $sessionService->getAllUserAssignedOrganogramIds();
    //     $baseTable = $query->getModel()->getTable();
    //     $tablePrefix = $this->getTablePrefix();

    //     if (!empty($organizationIds)) {
    //         if (CommonService::hasColumn($query->getModel()->getTable(), 'organization_ids')) {
    //             if (is_array($organizationIds)) {
    //                 $organizationIds = array_map('strval', $organizationIds);
    //                 $query->where(function ($subQuery) use ($organizationIds, $baseTable, $tablePrefix) {
    //                     $subQuery->whereExists(function ($query) use ($organizationIds, $baseTable, $tablePrefix) {
    //                         $query->select(DB::raw(1))
    //                             ->from("$baseTable AS new_table")
    //                             ->crossJoin(DB::raw("
    //                                     json_table(
    //                                         {$tablePrefix}new_table.organization_ids,
    //                                         '$[*]'
    //                                         columns (
    //                                             organization_id varchar(50) PATH '$'
    //                                         )
    //                                     ) {$tablePrefix}x
    //                               "))
    //                             ->whereIn('x.organization_id', $organizationIds)
    //                             ->whereColumn('new_table.id', $baseTable . ".id");
    //                     })
    //                         ->orWhereNull('organization_ids');
    //                 });
    //             } else {
    //                 $organizationIds = strval($organizationIds);
    //                 $query->where(function ($subQuery) use ($organizationIds) {
    //                     $subQuery->whereJsonContains('organization_ids', [$organizationIds])
    //                         ->orWhereNull('organization_ids');
    //                 });
    //             }
    //         } elseif (in_array('organization_id', $this->orgFilterFields) && CommonService::hasColumn($query->getModel()->getTable(), 'organization_id')) {
    //             if (is_array($organizationIds)) {
    //                 $query->where(function ($subQuery) use ($organizationIds) {
    //                     $subQuery->whereIn('organization_id', $organizationIds);
    //                 });
    //             } else {
    //                 $query->where(function ($subQuery) use ($organizationIds) {
    //                     $subQuery->where('organization_id', $organizationIds);
    //                 });
    //             }
    //         }
    //     }

    //     if (!empty($organogramIds) && in_array('organogram_id', $this->orgFilterFields) && CommonService::hasColumn($query->getModel()->getTable(), 'organogram_id')) {
    //         if (is_array($organogramIds)) {
    //             $query->where(function ($subQuery) use ($organogramIds) {
    //                 $subQuery->whereIn('organogram_id', $organogramIds);
    //             });
    //         } else {
    //             $query->where(function ($subQuery) use ($organogramIds) {
    //                 $subQuery->where('organogram_id', $organogramIds);
    //             });
    //         }
    //     }

    //     return $query;
    // }

    protected function applyAggregates(Builder $query, array $aggregates = []): Builder
    {
        if (empty($aggregates)) {
            return $query;
        }

        return $query;
    }

    protected function applyOrderBy(Builder $query, array $orders = []): Builder
    {
        if (empty($orders)) {
            return $query;
        }

        foreach ($orders as $item) {
            $itemOrderBy = explode(' ', $item);
            $query = $query->orderBy($itemOrderBy[0], $itemOrderBy[1]);
        }
        return $query;
    }

    protected function getTotal(Builder $query)
    {
        return $query->count();
    }

    protected function replaceDbPrefix($rawQuery, $prefix = 'db_prefix')
    {
        $rawQuery = trim($rawQuery);
        $tablePrefix = $this->getTableName();
        return str_replace($prefix, "`{$tablePrefix}`", $rawQuery);
    }

    public function getTablePrefix()
    {
        return DB::getTablePrefix();
    }

    public function getTableName()
    {
        $dbPrefix = $this->getTablePrefix();
        $tableName = $this->model->getTable();
        return $dbPrefix . $tableName;
    }

    public function findById($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function firstWhere($column, $value)
    {
        return $this->model->firstWhere("{$column}", $value);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(array $data, $id)
    {
        return $this->model->find($id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->find($id)->delete($id);
    }
}
