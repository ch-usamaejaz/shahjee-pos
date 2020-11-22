<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function get_user_orders(Request $request)
    {
        $response = [];

        try {
            $query = @Orders::select('id', 'order_status', 'order_total', 'order_discount')
                ->where('user_id', $request['user_id']);
            if (@$request['itemsPerPage'] !== -1) {
                $query->limit(@$request['itemsPerPage'])->offset(@$request['page'] - 1);
            };
            $orders = $query->orderBy('created_at', @$request['sortDesc'][0] ? 'DESC' : 'ASC')
                ->get()->toArray();

            $response = ['error' => false, 'orders' => empty($orders) ? [] : $orders];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function create_new_order(Request $request)
    {

        $order = @Orders::create($request->all());
        $response = ['error' => false, 'message' => 'success'];

        if (isset($request['items']) && sizeof(@$request['items']) > 0) {
            foreach ($request['items'] as $key => $item) {
                try {
                    $order->items()->attach($item['item_id'], ['quantity' => $item['quantity']]);
                } catch (\Exception $exception) {
                    $response = ['error' => true, 'message' => $exception->getMessage()];
                    break;
                }
            }
        }

        return response()->json($response);
    }

    public function delete_order(Request $request)
    {
        $id = @$request->order_id;
        $response = ['error' => false, 'message' => 'Order Deleted Successfully!'];
        try {
            Orders::find($id)->delete();
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    public function get_order(Request $request)
    {
        $id = @$request->order_id;
        $response = [];

        try {
            $orders_data = Orders::with('items')->where('id', $id)->get()->toArray();
            if (empty($orders_data)) throw new \Exception('No Data found against order ID.');
            $response = ['error' => false, 'data' => $orders_data];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);

    }

    public function update_order(Request $request)
    {
        $order = @Orders::updateOrCreate(['id' => @$request->order_id, 'user_id' => @$request->user_id], $request->all());

    }
}
