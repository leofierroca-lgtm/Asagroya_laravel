<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'Admin') {
            $totalRevenue = Sale::sum('total_amount');
            $totalSales = Sale::count();
            $totalCustomers = Customer::count();
            
            $lowStockAlerts = Product::whereColumn('stock', '<=', 'min_stock')->get();
            
            $recentSales = Sale::with('customer')->orderBy('sale_date', 'desc')->take(5)->get();
            
            return view('dashboard.admin', compact(
                'totalRevenue', 'totalSales', 'totalCustomers', 'lowStockAlerts', 'recentSales'
            ));
        } else {
            $allProducts = Product::all();
            return view('dashboard.seller', compact('allProducts'));
        }
    }
}
