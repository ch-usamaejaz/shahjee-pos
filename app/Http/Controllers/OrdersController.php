<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function get_user_orders(Request $request)
    {

        //        $orders_data = @Orders::with('items')->where('user_id',$request['user_id'])->get()->toArray();
        $orders = @Orders::select('id', 'order_status', 'order_total', 'order_discount')
            ->where('user_id', $request['user_id'])
            ->limit(@$request['itemsPerPage'])
            ->offset(@$request['page'] - 1)
            ->orderBy('created_at', @$request['sortDesc'][0] ? 'DESC' : 'ASC')
            ->get()->toArray();

        $response = ['error' => false, 'orders' => empty($orders) ? [] : $orders];

        return response()->json($response);

    }

    public function create_new_order(Request $request)
    {

        $order = @Orders::create($request->all());
        $response = ['error' => false,'message' => 'success'];

        if (isset($request['items']) && sizeof(@$request['items']) > 0) {
            foreach ($request['items'] as $key => $item) {
                try {
                    $order->items()->attach($item['item_id'], ['quantity' => $item['quantity']]);
                }
                catch (\Exception $exception) {
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
            $order = @Orders::find($id);
            if (empty($order)) throw new \Exception('No such order found');
            $order->items()->delete();
            $order->delete();
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }
}
