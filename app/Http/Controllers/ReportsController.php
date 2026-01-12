<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportsController extends Controller
{
    /**
     * Display inventory report page.
     */
    public function inventory(): View
    {
        return view('reports.inventory');
    }

    /**
     * Display transactions report page.
     */
    public function transactions(): View
    {
        return view('reports.transactions');
    }
}
