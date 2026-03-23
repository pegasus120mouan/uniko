<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $q = (string) $request->query('q', '');
        $status = (string) $request->query('status', '');

        $orders = Order::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('order_number', 'like', "%{$q}%")
                    ->orWhere('full_name', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('orders.index', [
            'orders' => $orders,
            'q' => $q,
            'status' => $status,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items']);

        return view('orders.show', [
            'order' => $order,
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending_confirmation,confirmed,canceled,delivered'],
        ]);

        $payload = [
            'status' => $validated['status'],
        ];

        if ($validated['status'] === 'confirmed' && $order->confirmed_at === null) {
            $payload['confirmed_at'] = now();
        }

        $order->update($payload);

        return redirect()
            ->route('admin.orders.show', $order)
            ->with('status', 'Statut mis à jour.');
    }
}
