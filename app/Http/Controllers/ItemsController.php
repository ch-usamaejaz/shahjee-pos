<?php

namespace App\Http\Controllers;

use App\Models\Items;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    public function get_all_items($origin, $limit = null, $offset = null)
    {
        $response = [];
        try {
            $query = Items::select('id', 'item_price', 'item_name');
            $items = $origin == 'orders' ? $query->withTrashed()->get()->toArray() : ($limit !== -1 ? $query->limit($limit)->offset($offset - 1)->get()->toArray() : $query->get()->toArray());
            $response = ['error' => false, 'data' => $items];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function delete_item(Request $request)
    {
        $response = ['error' => false, 'message' => 'Item deleted Successfully!'];

        try {
            Items::where('id', @$request->item_id)->delete();
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function update_item(Request $request)
    {
        $response = ['error' => false, 'message' => 'Item updated Successfully!'];

        try {
            $updated_item = Items::updateOrCreate(['id' => @$request->item_id], $request->all());
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function create_item(Request $request)
    {
        $response = ['error' => false, 'message' => 'Item created Successfully!'];

        try {
            Items::create($request->all());
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }
}

