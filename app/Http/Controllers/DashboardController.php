<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function get_dashboard_data()
    {
        $response = ['error' => false];
        try {
            list($yesterday_orders, $yesterday_sales) = self::get_date_sales_and_count('yesterday');
            list($today_orders, $today_sales) = self::get_date_sales_and_count('today');
            $percentage_diff = self::get_percentage_increase($yesterday_sales, $today_sales);
            $response['data'] = ['today_orders' => $today_orders, 'today_sales' => $today_sales, 'yesterday_sales' => $yesterday_sales, 'increase_percentage' => is_infinite($percentage_diff) || is_nan($percentage_diff) ? '' : round($percentage_diff, 2)];
        } catch (\Exception $exception) {
            $response = ['error' => true, 'message' => $exception->getMessage()];
        }

        return response()->json($response);
    }

    private function get_date_sales_and_count($date)
    {
        $sales = $count = 0;
        $today = Carbon::today()->addHours(4)->toDateTimeString();
        $yesterday = Carbon::yesterday()->addHours(4)->toDateTimeString();
        $coming_day = Carbon::today()->addHours(4)->addDay(1)->toDateTimeString();

        $orders = $date === 'today' ? @Orders::where([['created_at', '>=', $today], ['created_at', '<', $coming_day]])->pluck('order_total')->toArray() : @Orders::where([['created_at', '>=', $yesterday], ['created_at', '<', $today]])->pluck('order_total')->toArray();
        $count = sizeof($orders);
        $sales = array_sum($orders);
        return array($count, $sales);
    }

    private function get_percentage_increase($old, $new)
    {
        $increase_value = $new - $old;
        return @($increase_value / $old) * 100;
    }
}
