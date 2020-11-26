<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';
    protected $fillable = ['item_name', 'item_price', 'quantity'];
    protected $hidden = ['updated_at'];

}
