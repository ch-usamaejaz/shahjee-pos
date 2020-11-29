<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreController extends Controller
{
    public function get_all_items($limit = null, $offset = null)
    {
        $response = [];
        try {
            $query = DB::table('store')->select('id', 'item_price', 'item_name', 'quantity', DB::raw('DATE_FORMAT(store.created_at, "%d-%b-%Y") as created_at'));
            if ($limit != -1) {
                $query->limit($limit)->offset(($offset - 1) * $limit);
            }
            $items = $query->get()->toArray();
            $response = ['error' => false, 'data' => $items, 'total_store_items' => count(Store::get())];
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
