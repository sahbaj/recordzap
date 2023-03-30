<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderMeta extends Model
{
    protected $connection = 'mysql_wp';
    protected $table = 'zap_wpforms_entry_meta';
    protected $primaryKey = 'id';
    public $timestamps = false;

    use HasFactory;
    use Filterable;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

   
}
