<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function get_all_items()
    {
        $response = [];
        try {
            $items = Items::select('id','item_price', 'item_name')->get()->toArray();
            $response = ['error' => false, 'data' => $items];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }
}

