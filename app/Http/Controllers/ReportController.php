<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->subDays(30)->toDateString());
        $endDate = $request->get('end_date', now()->toDateString());

        $sales = Sale::with('customer', 'user')
            ->whereDate('sale_date', '>=', $startDate)
            ->whereDate('sale_date', '<=', $endDate)
            ->orderBy('sale_date', 'desc')
            ->get();

        $totalRevenue = $sales->sum('total_amount');
        $totalTransactions = $sales->count();

        return view('reports.sales', compact('sales', 'startDate', 'endDate', 'totalRevenue', 'totalTransactions'));
    }

    public function inventory()
    {
        $products = Product::orderBy('name')->get();

        $totalValuation = $products->sum(function($p) {
            return $p->stock * $p->purchase_price;
        });

        $lowStockItems = $products->filter(function($p) {
            return $p->stock <= $p->min_stock;
        });

        return view('reports.inventory', compact('products', 'totalValuation', 'lowStockItems'));
    }
}
