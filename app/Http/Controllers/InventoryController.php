<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    /**
     * Display stock in page.
     */
    public function stockIn(): View
    {
        return view('inventory.stock-in');
    }

    /**
     * Display stock out page.
     */
    public function stockOut(): View
    {
        return view('inventory.stock-out');
    }

    /**
     * Display stock history page.
     */
    public function history(): View
    {
        return view('inventory.history');
    }
}
