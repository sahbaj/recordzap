<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class OrderFilter extends ModelFilter
{
    /**
     * Related Models that have ModelFilters as well as the method on the ModelFilter
     * As [relationMethod => [input_key1, input_key2]].
     *
     * @var array
     */
    public $relations = [];

    public function search($query)
    {
        return $this->Where('entry_id', 'LIKE', '%' . $query . '%')
            ->orWhere('form_id', 'LIKE', '%' . $query . '%')
            ->orWhere('meta', 'LIKE', '%' . $query . '%');
    }
}
