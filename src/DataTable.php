<?php

namespace Dginanjar\CodeIgniterDataTable;

use Config\Services;

class DataTable
{
    protected static $model;
    protected static $draw, $start, $length, $search, $order, $columns;

    public static function get($model)
    {
        self::init($model);

        self::filtering();
        self::ordering();

        $recordsFiltered = count(self::$model->builder()->get(null, 0, false)->getResult());
        $records = self::$model->findAll(self::$length, self::$start);
        $recordsTotal = count($model->findAll());

        return [
            'draw' => self::$draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => array_values($records),
        ];
    }

    protected static function init($model)
    {
        self::$model = $model;
        self::$draw = Services::request()->getGet('draw');
        self::$start = Services::request()->getGet('start');
        self::$length = Services::request()->getGet('length');
        self::$search = Services::request()->getGet('search');
        self::$order = Services::request()->getGet('order');
        self::$columns = Services::request()->getGet('columns');
    }

    protected static function filtering()
    {
        if (empty(self::$search['value'])) return self::$model;

        foreach (self::$columns as $column) {
            if ($column['searchable'] === 'true') {
                self::$model->orHavingLike($column['data'], self::$search['value']);
                if (!empty($column['search']['value'])) {
                    self::$model->havingLike($column['data'], $column['search']['value']);
                }
            }
        }

        return self::$model;
    }

    protected static function ordering()
    {
        foreach (self::$order as $order) {
            if (self::$columns[$order['column']]['orderable'] === 'true') {
                self::$model->orderBy(self::$columns[$order['column']]['data'], $order['dir']);
            }
        }

        return self::$model;
    }

    protected static function getRecords()
    {
        return self::ordering(self::filtering())->findAll(self::$length, self::$start);
    }
}
