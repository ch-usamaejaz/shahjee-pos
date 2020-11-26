<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    protected $table = 'store';
    protected $fillable = ['item_name', 'item_price', 'quantity'];
    protected $hidden = ['updated_at'];
    use SoftDeletes;

}
