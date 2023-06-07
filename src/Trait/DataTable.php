<?php

namespace Dginanjar\CodeIgniterDataTable\Trait;

trait DataTable
{
    public function datatable()
    {
        return \Dginanjar\CodeIgniterDataTable\DataTable::get($this);
    }
}