<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class LanguageController extends Controller
{
    /**
     * Switch application language
     *
     * @param Request $request
     * @param string $locale
     * @return RedirectResponse
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validate locale
        if (!in_array($locale, ['id', 'en'])) {
            abort(404);
        }
        
        // Store in session
        session(['app_locale' => $locale]);
        
        // Redirect back
        return redirect()->back();
    }
}