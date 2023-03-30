<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

class OrderForm extends Model
{
    protected $connection = 'mysql_wp';
    protected $table = 'zap_posts';
    protected $primaryKey = 'ID';
    public $timestamps = false;
    
    
}
