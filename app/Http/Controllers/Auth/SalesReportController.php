<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReportController extends Controller
{
    // Total sales, orders, and revenue
    public function summary()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        $topProducts = Product::withCount(['orders as sold' => function($q){
            $q->select(DB::raw("SUM(order_items.quantity)"));
        }])->orderByDesc('sold')->take(5)->get();

        return response()->json([
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'top_products' => $topProducts
        ]);
    }
}
