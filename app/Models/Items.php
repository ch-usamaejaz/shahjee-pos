<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Items extends Model
{
    protected $table = 'items';
    protected $fillable = ['item_name', 'item_price'];
    use SoftDeletes;

    public function orders()
    {
        return $this->belongsToMany('App\Models\Orders');
    }
}
