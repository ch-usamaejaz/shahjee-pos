<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function get_user_orders (Request $request) {

        //        $orders_data = @Orders::with('items')->where('user_id',$request['user_id'])->get()->toArray();
        $orders = @Orders::select('id', 'order_status', 'order_total')
            ->where('user_id',$request['user_id'])
            ->limit(@$request['itemsPerPage'])
            ->offset(@$request['page'] - 1)
            ->orderBy('created_at', @$request['sortDesc'][0]  ? 'DESC' : 'ASC')
            ->get()->toArray();

        $response = ['error' => false, 'orders' => empty($orders) ? [] : $orders];

        return response()->json($response);

    }

    public function create_new_order (Request $request) {

        $order = @Orders::create($request->all());

        if (isset($request['items']) &&  sizeof(@$request['items']) > 0) {
            foreach ($request['items'] as $key => $item) {
                @$order->items()->attach($item['item_id'], ['quantity' => $item['quantity']]);
            }
        }

        return response()->json(['message' => 'success'], 200);

    }
}
