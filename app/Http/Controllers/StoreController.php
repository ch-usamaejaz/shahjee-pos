<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function get_all_items($origin, $limit = null, $offset = null)
    {
        $response = [];
        try {
            $items = Store::select('id', 'item_price', 'item_name', 'quantity', 'created_at')->get()->toArray();
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
            Store::where('id', @$request->item_id)->delete();
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function update_item(Request $request)
    {
        $response = ['error' => false, 'message' => 'Item updated Successfully!'];

        try {
            $updated_item = Store::updateOrCreate(['id' => @$request->item_id], $request->all());
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function create_item(Request $request)
    {
        $response = ['error' => false, 'message' => 'Item created Successfully!'];

        try {
            Store::create($request->all());
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }
}
