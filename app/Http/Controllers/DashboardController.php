<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $now = now();
        $todayStart = $now->copy()->startOfDay();
        $weekStart = $now->copy()->startOfWeek();
        $monthStart = $now->copy()->startOfMonth();

        $dailySalesCount = Sale::query()->where('sold_at', '>=', $todayStart)->count();
        $weeklySalesCount = Sale::query()->where('sold_at', '>=', $weekStart)->count();
        $monthlySalesCount = Sale::query()->where('sold_at', '>=', $monthStart)->count();

        $dailyRevenue = (float) Sale::query()->where('sold_at', '>=', $todayStart)->sum('total');
        $weeklyRevenue = (float) Sale::query()->where('sold_at', '>=', $weekStart)->sum('total');
        $monthlyRevenue = (float) Sale::query()->where('sold_at', '>=', $monthStart)->sum('total');

        $totalRevenue = (float) Sale::query()->sum('total');
        $totalSalesCount = Sale::query()->count();
        $totalProductsCount = Product::query()->count();

        $lowStockProducts = Product::query()
            ->with('category')
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->orderBy('quantity')
            ->limit(8)
            ->get();

        $lowStockCount = Product::query()
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->count();

        $topProducts = SaleItem::query()
            ->selectRaw('product_id, SUM(quantity) as qty_sold, SUM(line_total) as amount')
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('qty_sold')
            ->limit(8)
            ->get();

        return view('dashboard', [
            'dailySalesCount' => $dailySalesCount,
            'weeklySalesCount' => $weeklySalesCount,
            'monthlySalesCount' => $monthlySalesCount,
            'dailyRevenue' => $dailyRevenue,
            'weeklyRevenue' => $weeklyRevenue,
            'monthlyRevenue' => $monthlyRevenue,
            'totalRevenue' => $totalRevenue,
            'totalSalesCount' => $totalSalesCount,
            'totalProductsCount' => $totalProductsCount,
            'lowStockProducts' => $lowStockProducts,
            'lowStockCount' => $lowStockCount,
            'topProducts' => $topProducts,
        ]);
    }
}
