<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Route::get('/login', function () {
    return view('login');
})->middleware('guest')->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'login' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    $remember = $request->boolean('remember');

    if (!Auth::attempt($credentials, $remember)) {
        return back()
            ->withErrors(['login' => 'Identifiants incorrects.'])
            ->onlyInput('login');
    }

    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
})->middleware('guest')->name('login.perform');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:admin,staff'])
    ->name('dashboard');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    })->name('home');

    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('products', ProductController::class)->except(['show']);

    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    Route::get('/stock/alerts', [StockController::class, 'alerts'])->name('stock.alerts');
    Route::get('/stock/movements', [StockController::class, 'movements'])->name('stock.movements');
    Route::get('/stock/adjust', [StockController::class, 'adjustForm'])->name('stock.adjust.form');
    Route::post('/stock/adjust', [StockController::class, 'adjust'])->name('stock.adjust');

    Route::get('/reports/inventory', [ReportsController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/inventory.csv', [ReportsController::class, 'inventoryCsv'])->name('reports.inventory.csv');

    Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/sales.csv', [ReportsController::class, 'salesCsv'])->name('reports.sales.csv');
    Route::get('/reports/sales.pdf', [ReportsController::class, 'salesPdf'])->name('reports.sales.pdf');

    Route::get('/reports/revenue', [ReportsController::class, 'revenue'])->name('reports.revenue');
});
