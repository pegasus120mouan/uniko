<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $from = (string) $request->query('from', '');
        $to = (string) $request->query('to', '');

        $sales = Sale::query()
            ->with(['user'])
            ->when($q !== '', function ($query) use ($q) {
                $query->where('invoice_number', 'like', "%{$q}%")
                    ->orWhereHas('items.product', function ($p) use ($q) {
                        $p->where('name', 'like', "%{$q}%")
                            ->orWhere('brand', 'like', "%{$q}%");
                    });
            })
            ->when($from !== '', fn ($query) => $query->whereDate('sold_at', '>=', $from))
            ->when($to !== '', fn ($query) => $query->whereDate('sold_at', '<=', $to))
            ->orderByDesc('sold_at')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', [
            'sales' => $sales,
            'q' => $q,
            'from' => $from,
            'to' => $to,
        ]);
    }

    public function create(): View
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('sales.create', [
            'products' => $products,
        ]);
    }

    public function store(Request $request, StockService $stockService): RedirectResponse
    {
        $validated = $request->validate([
            'sold_at' => ['required', 'date'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'tax' => ['nullable', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $discount = (float) ($validated['discount'] ?? 0);
        $tax = (float) ($validated['tax'] ?? 0);

        try {
            $sale = DB::transaction(function () use ($validated, $discount, $tax, $request, $stockService) {
                $invoiceNumber = $this->generateInvoiceNumber();

                $sale = Sale::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => $request->user()?->id,
                    'sold_at' => $validated['sold_at'],
                    'subtotal' => 0,
                    'discount' => $discount,
                    'tax' => $tax,
                    'total' => 0,
                ]);

                $subtotal = 0.0;

                foreach ($validated['items'] as $item) {
                    $product = Product::query()->findOrFail($item['product_id']);
                    $qty = (int) $item['quantity'];
                    $unitPrice = (float) $product->price;
                    $lineTotal = $unitPrice * $qty;

                    SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => $qty,
                        'unit_price' => $unitPrice,
                        'line_total' => $lineTotal,
                    ]);

                    $subtotal += $lineTotal;

                    $stockService->adjust(
                        product: $product,
                        quantityChange: -$qty,
                        type: 'sale',
                        user: $request->user(),
                        reference: $invoiceNumber,
                        comment: 'Vente ' . $invoiceNumber,
                        movedAt: now(),
                    );
                }

                $total = max(0, $subtotal - $discount + $tax);

                $sale->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);

                return $sale;
            });
        } catch (ValidationException $e) {
            throw $e;
        }

        return redirect()
            ->route('admin.sales.show', $sale)
            ->with('status', 'Vente enregistrée avec succès.');
    }

    public function show(Sale $sale): View
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', [
            'sale' => $sale,
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        $date = now()->format('Ymd');

        for ($i = 0; $i < 10; $i++) {
            $suffix = Str::upper(Str::random(6));
            $invoice = "UNK-{$date}-{$suffix}";

            if (!Sale::query()->where('invoice_number', $invoice)->exists()) {
                return $invoice;
            }
        }

        return 'UNK-' . $date . '-' . (string) Str::uuid();
    }
}
