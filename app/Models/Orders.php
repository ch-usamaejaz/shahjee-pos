<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected  $table = 'orders';
    protected $fillable = [ 'user_id', 'order_status', 'order_total' ];

    public function items()
    {
        return $this->belongsToMany('App\Models\Items')->withPivot('quantity');
    }

}
