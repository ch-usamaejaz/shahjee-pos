<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    //
    protected  $table = 'orders';
    protected $fillable = [ 'user_id', 'order_status', 'order_total', 'order_discount', 'order_shift' ];
    protected $hidden = ['created_at', 'updated_at'];

    public function items()
    {
        return $this->belongsToMany('App\Models\Items')->withTrashed()->select(['items.id as item_id','item_name', 'item_price', 'quantity']);
    }

}
