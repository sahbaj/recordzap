<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class SubscriptionFilter extends ModelFilter
{

    public function search($query)
    {
        return $this->Where('entry_id', 'LIKE', '%' . $query . '%')
            ->orWhere('form_id', 'LIKE', '%' . $query . '%')
            ->orWhere('status', 'LIKE', '%' . $query . '%');
    }
}
