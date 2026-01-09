<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFacade;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ReportsController extends Controller
{
    public function inventory(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $categoryId = (string) $request->query('category_id', '');
        $onlyLow = (bool) $request->boolean('low', false);
        $onlyActive = (bool) $request->boolean('active', false);

        $products = Product::query()
            ->with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->when($categoryId !== '', fn ($query) => $query->where('category_id', $categoryId))
            ->when($onlyActive, fn ($query) => $query->where('is_active', true))
            ->when($onlyLow, fn ($query) => $query->whereColumn('quantity', '<=', 'low_stock_threshold'))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $categories = Category::query()->orderBy('name')->get();

        return view('reports.inventory', [
            'products' => $products,
            'categories' => $categories,
            'q' => $q,
            'categoryId' => $categoryId,
            'onlyLow' => $onlyLow,
            'onlyActive' => $onlyActive,
        ]);
    }

    public function inventoryCsv(Request $request): Response
    {
        $q = (string) $request->query('q', '');
        $categoryId = (string) $request->query('category_id', '');
        $onlyLow = (bool) $request->boolean('low', false);
        $onlyActive = (bool) $request->boolean('active', false);

        $products = Product::query()
            ->with('category')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($q2) use ($q) {
                    $q2->where('name', 'like', "%{$q}%")
                        ->orWhere('brand', 'like', "%{$q}%");
                });
            })
            ->when($categoryId !== '', fn ($query) => $query->where('category_id', $categoryId))
            ->when($onlyActive, fn ($query) => $query->where('is_active', true))
            ->when($onlyLow, fn ($query) => $query->whereColumn('quantity', '<=', 'low_stock_threshold'))
            ->orderBy('name')
            ->get();

        $filename = 'inventaire-' . now()->format('Ymd-His') . '.csv';

        return ResponseFacade::streamDownload(function () use ($products) {
            $out = fopen('php://output', 'w');

            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['ID', 'Nom', 'Marque', 'Catégorie', 'Prix', 'Stock', 'Seuil', 'Actif']);

            foreach ($products as $p) {
                fputcsv($out, [
                    $p->id,
                    $p->name,
                    $p->brand,
                    $p->category?->name,
                    $p->price,
                    $p->quantity,
                    $p->low_stock_threshold,
                    $p->is_active ? 'Oui' : 'Non',
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function sales(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');

        $sales = Sale::query()
            ->with(['user'])
            ->when($q !== '', fn ($query) => $query->where('invoice_number', 'like', "%{$q}%"))
            ->when($from !== '', fn ($query) => $query->whereDate('sold_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('sold_at', '<=', $to))
            ->orderByDesc('sold_at')
            ->paginate(20)
            ->withQueryString();

        $totals = Sale::query()
            ->when($q !== '', fn ($query) => $query->where('invoice_number', 'like', "%{$q}%"))
            ->when($from !== '', fn ($query) => $query->whereDate('sold_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('sold_at', '<=', $to))
            ->selectRaw('COUNT(*) as cnt, COALESCE(SUM(total),0) as amount')
            ->first();

        return view('reports.sales', [
            'sales' => $sales,
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'totalCount' => (int) ($totals->cnt ?? 0),
            'totalAmount' => (float) ($totals->amount ?? 0),
        ]);
    }

    public function salesCsv(Request $request): Response
    {
        $q = (string) $request->query('q', '');
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');

        $sales = Sale::query()
            ->with(['user'])
            ->when($q !== '', fn ($query) => $query->where('invoice_number', 'like', "%{$q}%"))
            ->when($from !== '', fn ($query) => $query->whereDate('sold_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('sold_at', '<=', $to))
            ->orderByDesc('sold_at')
            ->get();

        $filename = 'ventes-' . now()->format('Ymd-His') . '.csv';

        return ResponseFacade::streamDownload(function () use ($sales) {
            $out = fopen('php://output', 'w');

            fwrite($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($out, ['ID', 'Date', 'Facture', 'Utilisateur', 'Sous-total', 'Remise', 'Taxe', 'Total']);

            foreach ($sales as $s) {
                fputcsv($out, [
                    $s->id,
                    optional($s->sold_at)->format('Y-m-d H:i:s'),
                    $s->invoice_number,
                    $s->user?->name,
                    $s->subtotal,
                    $s->discount,
                    $s->tax,
                    $s->total,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function salesPdf(Request $request): Response
    {
        $q = (string) $request->query('q', '');
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');

        if (!class_exists('Barryvdh\\DomPDF\\Facade\\Pdf')) {
            return response(
                "PDF non disponible. Installe d'abord barryvdh/laravel-dompdf via Composer.",
                501
            );
        }

        $sales = Sale::query()
            ->with(['user', 'items.product'])
            ->when($q !== '', fn ($query) => $query->where('invoice_number', 'like', "%{$q}%"))
            ->when($from !== '', fn ($query) => $query->whereDate('sold_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('sold_at', '<=', $to))
            ->orderByDesc('sold_at')
            ->get();

        $totals = [
            'count' => $sales->count(),
            'amount' => (float) $sales->sum('total'),
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.sales_pdf', [
            'sales' => $sales,
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'totals' => $totals,
        ]);

        $name = 'rapport-ventes-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($name);
    }

    public function revenue(Request $request): View
    {
        $days = max(7, min(365, (int) $request->query('days', 30)));
        $months = max(3, min(36, (int) $request->query('months', 12)));

        $daily = Sale::query()
            ->whereDate('sold_at', '>=', now()->subDays($days)->toDateString())
            ->selectRaw('DATE(sold_at) as d, COALESCE(SUM(total),0) as amount, COUNT(*) as cnt')
            ->groupBy('d')
            ->orderBy('d')
            ->get();

        $monthly = Sale::query()
            ->whereDate('sold_at', '>=', now()->subMonths($months)->startOfMonth()->toDateString())
            ->selectRaw("DATE_FORMAT(sold_at, '%Y-%m') as m, COALESCE(SUM(total),0) as amount, COUNT(*) as cnt")
            ->groupBy('m')
            ->orderBy('m')
            ->get();

        return view('reports.revenue', [
            'days' => $days,
            'months' => $months,
            'dailyLabels' => $daily->pluck('d')->values(),
            'dailyAmounts' => $daily->pluck('amount')->map(fn ($v) => (float) $v)->values(),
            'dailyCounts' => $daily->pluck('cnt')->map(fn ($v) => (int) $v)->values(),
            'monthlyLabels' => $monthly->pluck('m')->values(),
            'monthlyAmounts' => $monthly->pluck('amount')->map(fn ($v) => (float) $v)->values(),
            'monthlyCounts' => $monthly->pluck('cnt')->map(fn ($v) => (int) $v)->values(),
        ]);
    }
}
