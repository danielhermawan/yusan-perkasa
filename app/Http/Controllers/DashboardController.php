<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\Controller;

class DashboardController extends Controller
{
    public function purchasing()
    {
        return view("pages.dashboard.pembelian");
    }

    public function sales()
    {
        return view("pages.dashboard.penjualan");
    }
}
