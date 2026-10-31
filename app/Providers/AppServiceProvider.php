<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Transaksi;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Gunakan query yang lebih ringan (sum saja)
        View::composer('layouts.admin', function ($view) {
            $view->with('totalPendapatan', Transaksi::sum('total_price'));
        });
    }
}