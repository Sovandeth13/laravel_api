<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    // Generate sales report
    public function generate(Request $request)
    {
        // Example dummy data - replace with real logic
        $report = [
            'total_sales' => 10000,
            'total_orders' => 150,
            'period' => $request->input('period', 'monthly'),
        ];

        return response()->json($report);
    }
}
