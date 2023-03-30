<?php

namespace App\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    protected $connection = 'mysql_wp';
    protected $table = 'zap_wpforms_entries';
    protected $primaryKey = 'entry_id';
    public $timestamps = false;
    protected $casts = [
        'fields' => 'array',
        'meta' => 'object',
    ];

    use HasFactory;
    // use SoftDeletes;
    use Filterable;

    public function order_form()
    {
        return $this->belongsTo(OrderForm::class,'form_id','ID');
    }
    
    public function subscription()
    {
        return $this->setConnection('mysql')->belongsTo(Subscription::class,'entry_id','entry_id');
        
    }

    public function metas()
    {
        return $this->hasMany(OrderMeta::class, 'entry_id', 'entry_id');
    }
}
