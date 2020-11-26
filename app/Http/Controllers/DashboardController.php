<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function get_dashboard_data()
    {
        $response = ['error' => false];
        try {
            list($yesterday_orders, $yesterday_sales) = self::get_date_sales_and_count(Carbon::yesterday());
            list($today_orders, $today_sales) = self::get_date_sales_and_count(Carbon::today());
            $percentage_diff = self::get_percentage_increase($yesterday_sales, $today_sales);
            $response['data'] = ['today_orders' => $today_orders, 'today_sales' => $today_sales , 'yesterday_sales' => $yesterday_sales, 'increase_percentage' => is_infinite($percentage_diff) ? '' : $percentage_diff ];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    private function get_date_sales_and_count($date)
    {
        $sales = $count = 0;

        if (!empty($date)) {
            $orders = @Orders::whereDate('created_at', $date)->pluck('order_total')->toArray();
            $count = sizeof($orders);
            $sales = array_sum($orders);
        }

        return array($count, $sales);
    }

    private function get_percentage_increase($old, $new)
    {
        $increase_value = $new - $old;
        return @($increase_value / $old) * 100;
    }
}
